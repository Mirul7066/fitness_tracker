<?php
session_start();
require_once 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Include shared header (with navbar)
include 'includes/header.php';
?>

<!-- Dashboard Content -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Dashboard</h2>
    <div class="text-center">
        <p>Here’s where you can track your workouts, progress, and fitness goals!</p>
        <a href="add_entry.php" class="btn btn-success">+ Add Fitness Entry</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
