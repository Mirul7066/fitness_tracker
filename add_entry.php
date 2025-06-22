<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';
include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id']; 
    $activity_date = $_POST['activity_date'];
    $activity_type = $_POST['activity_type'];
    $duration = $_POST['duration_minutes'];
    $calories = $_POST['calories_burned'];

    try {
        $stmt = $pdo->prepare("INSERT INTO entries (user_id, activity_date, activity_type, duration_minutes, calories_burned, created_at)
                               VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $activity_date, $activity_type, $duration, $calories]);
        header("Location: view_entries.php");
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<h2 class="mb-4">Add Fitness Entry</h2>

<form method="POST">
    <div class="mb-3">
        <label for="activity_date" class="form-label">Date</label>
        <input type="date" name="activity_date" id="activity_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="activity_type" class="form-label">Activity Type</label>
        <input type="text" name="activity_type" id="activity_type" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="duration_minutes" class="form-label">Duration (minutes)</label>
        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="calories_burned" class="form-label">Calories Burned</label>
        <input type="number" name="calories_burned" id="calories_burned" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Add Entry</button>
</form>

<?php include 'includes/footer.php'; ?>
