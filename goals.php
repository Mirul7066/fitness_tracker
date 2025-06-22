<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Handle adding a new goal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $goal_type = trim($_POST['goal_type']);
    $target_value = intval($_POST['target_value']);

    if ($goal_type && $target_value > 0) {
        $stmt = $pdo->prepare("INSERT INTO goals (user_id, goal_type, target_value, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $goal_type, $target_value]);
        $message = "<div class='alert alert-success'>Goal added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Please enter a valid goal type and target value.</div>";
    }
}

// Handle deleting a goal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $goal_id = intval($_POST['goal_id']);
    $stmt = $pdo->prepare("DELETE FROM goals WHERE id = ? AND user_id = ?");
    $stmt->execute([$goal_id, $user_id]);
    $message = "<div class='alert alert-warning'>Goal deleted.</div>";
}

// Handle updating a goal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $goal_id = intval($_POST['goal_id']);
    $goal_type = trim($_POST['goal_type']);
    $target_value = intval($_POST['target_value']);

    if ($goal_type && $target_value > 0) {
        $stmt = $pdo->prepare("UPDATE goals SET goal_type = ?, target_value = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$goal_type, $target_value, $goal_id, $user_id]);
        $message = "<div class='alert alert-info'>Goal updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Invalid update data.</div>";
    }
}

// Fetch goals for display
$stmt = $pdo->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if editing
$editing_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
?>

<div class="container mt-4">
    <h2 class="mb-4">Your Fitness Goals</h2>

    <?= $message ?>

    <!-- Add Goal Form -->
    <form method="POST" class="mb-4">
        <input type="hidden" name="action" value="add">
        <div class="row">
            <div class="col-md-5 mb-2">
                <input type="text" name="goal_type" class="form-control" placeholder="Goal Type (e.g. Steps, Calories)" required>
            </div>
            <div class="col-md-4 mb-2">
                <input type="number" name="target_value" class="form-control" placeholder="Target Value" required>
            </div>
            <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-primary w-100">Add Goal</button>
            </div>
        </div>
    </form>

    <!-- Goals Table -->
    <?php if ($goals): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Goal Type</th>
                    <th>Target Value</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($goals as $goal): ?>
                    <?php if ($editing_id === (int)$goal['id']): ?>
                        <!-- Edit Form Row -->
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">
                                <td><input type="text" name="goal_type" class="form-control" value="<?= htmlspecialchars($goal['goal_type']) ?>" required></td>
                                <td><input type="number" name="target_value" class="form-control" value="<?= $goal['target_value'] ?>" required></td>
                                <td><?= htmlspecialchars($goal['created_at']) ?></td>
                                <td>
                                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                                    <a href="goals.php" class="btn btn-secondary btn-sm">Cancel</a>
                                </td>
                            </form>
                        </tr>
                    <?php else: ?>
                        <!-- Normal Display Row -->
                        <tr>
                            <td><?= htmlspecialchars($goal['goal_type']) ?></td>
                            <td><?= htmlspecialchars($goal['target_value']) ?></td>
                            <td><?= htmlspecialchars($goal['created_at']) ?></td>
                            <td class="d-flex gap-1">
                                <a href="goals.php?edit=<?= $goal['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this goal?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">You haven't set any goals yet.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
