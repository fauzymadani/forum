<?php
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $thread_id = $_POST['thread_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO comments (thread_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$thread_id, $user_id, $content]);

        header("Location: thread.php?id=$thread_id");
        exit;
    } else {
        echo "Comment cannot be empty.";
    }
}
?>

<h1>Add a Comment</h1>
<form method="POST">
    <input type="hidden" name="thread_id" value="<?= htmlspecialchars($_POST['thread_id']) ?>">
    <textarea name="content" required></textarea>
    <button type="submit">Post Comment</button>
</form>

