<?php
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO threads (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $title, $content]);

    header('Location: index.php');
    exit;
}
?>

<h1>Create Thread</h1>
<form method="POST">
    <label>Title:</label>
    <input type="text" name="title" required>
    <label>Content:</label>
    <textarea name="content" required></textarea>
    <button type="submit">Save</button>
</form>

