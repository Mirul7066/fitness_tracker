<?php
require_once 'includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

    try {
        $stmt->execute([$username, $email, $password]);
        $message = "Registration successful! You can now <a href='login.php'>log in</a>.";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center">Create an Account</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mx-auto mt-4" style="max-width: 400px;">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>

        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a>.</p>
        <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">← Back to Home</a>
    </form>
</div>
</body>
</html>
