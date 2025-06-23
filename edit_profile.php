<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];

    // Update username and email
    $updateQuery = "UPDATE users SET username = ?, email = ?" . ($newPassword ? ", password = ?" : "") . " WHERE id = ?";
    $params = [$newUsername, $newEmail];

    if ($newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $params[] = $hashedPassword;
    }

    $params[] = $user_id;

    $stmt = $pdo->prepare($updateQuery);
    if ($stmt->execute($params)) {
        $success = "Profile updated successfully.";
        $_SESSION['username'] = $newUsername; // Update session name if changed
    } else {
        $error = "Failed to update profile.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h2>Edit Profile</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4" style="max-width: 600px;">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required value="<?= htmlspecialchars($user['username']) ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password (leave blank to keep current):</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="dashboard.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
