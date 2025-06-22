<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM entries WHERE user_id = ? ORDER BY activity_date DESC");
    $stmt->execute([$user_id]);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Your Fitness Entries</h2>
    <a href="add_entry.php" class="btn btn-success">➕ Add New Entry</a>
</div>

<?php if (!empty($entries)) : ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Activity</th>
                <th>Duration (min)</th>
                <th>Calories Burned</th>
                <th>Logged At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry) : ?>
                <tr>
                    <td><?= htmlspecialchars($entry['activity_date']) ?></td>
                    <td><?= htmlspecialchars($entry['activity_type']) ?></td>
                    <td><?= htmlspecialchars($entry['duration_minutes']) ?></td>
                    <td><?= htmlspecialchars($entry['calories_burned']) ?></td>
                    <td><?= htmlspecialchars($entry['created_at']) ?></td>
                    <td class="d-flex gap-1">
                        <a href="edit_entry.php?id=<?= $entry['id'] ?>" class="btn btn-sm btn-primary">Edit</a>

                        <form action="delete_entry.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                            <input type="hidden" name="entry_id" value="<?= $entry['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <div class="alert alert-info">No entries found. <a href="add_entry.php">Add one now</a>.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
