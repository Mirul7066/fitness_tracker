<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['entry_id'])) {
    $entry_id = $_POST['entry_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM entries WHERE id = ? AND user_id = ?");
        $stmt->execute([$entry_id, $user_id]);
    } catch (PDOException $e) {
        echo "Error deleting entry: " . $e->getMessage();
    }
}

header("Location: view_entries.php");
exit;
