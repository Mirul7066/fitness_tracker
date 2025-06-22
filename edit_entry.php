<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$entry_id = $_GET['id'] ?? null;

if (!$entry_id) {
    echo "<div class='alert alert-danger'>Invalid entry ID.</div>";
    include 'includes/footer.php';
    exit;
}

// Fetch the existing entry
$stmt = $pdo->prepare("SELECT * FROM entries WHERE id = ? AND user_id = ?");
$stmt->execute([$entry_id, $user_id]);
$entry = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$entry) {
    echo "<div class='alert alert-danger'>Entry not found.</div>";
    include 'includes/footer.php';
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $activity_date = $_POST['activity_date'];
    $activity_type = $_POST['activity_type'];
    $duration = $_POST['duration_minutes'];
    $calories = $_POST['calories_burned'];

    try {
        $update = $pdo->prepare("UPDATE entries SET activity_date = ?, activity_type = ?, duration_minutes = ?, calories_burned = ? WHERE id = ? AND user_id = ?");
        $update->execute([$activity_date, $activity_type, $duration, $calories, $entry_id, $user_id]);

        header("Location: view_entries.php");
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<h2 class="mb-4">Edit Fitness Entry</h2>

<form method="POST">
    <div class="mb-3">
        <label for="activity_date" class="form-label">Date</label>
        <input type="date" name="activity_date" id="activity_date" class="form-control" value="<?= htmlspecialchars($entry['activity_date']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="activity_type" class="form-label">Activity Type</label>
        <input type="text" name="activity_type" id="activity_type" class="form-control" value="<?= htmlspecialchars($entry['activity_type']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="duration_minutes" class="form-label">Duration (minutes)</label>
        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="<?= htmlspecialchars($entry['duration_minutes']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="calories_burned" class="form-label">Calories Burned</label>
        <input type="number" name="calories_burned" id="calories_burned" class="form-control" value="<?= htmlspecialchars($entry['calories_burned']) ?>">
    </div>

    <button type="submit" class="btn btn-success">Update Entry</button>
    <a href="view_entries.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'includes/footer.php'; ?>

