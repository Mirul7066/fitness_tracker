<?php
session_start();

// Redirect logged-in users to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Landing Page Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Fitness Tracker</a>
        <div class="d-flex">
            <a href="login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="register.php" class="btn btn-success">Register</a>
        </div>
    </div>
</nav>

<!-- Intro Section -->
<div class="container mt-5 text-center">
    <h1 class="mb-4">Welcome to Fitness Tracker</h1>
    <p class="lead">Track your workouts, set goals, and monitor your fitness progress all in one place.</p>
    <img src="images/fitness_banner.jpg" alt="Fitness" class="img-fluid mt-4" style="max-height: 300px;">
</div>

</body>
</html>

<?php include 'includes/footer.php'; ?>