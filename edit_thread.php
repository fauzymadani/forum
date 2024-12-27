<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$thread_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM threads WHERE id = ? AND user_id = ?");
$stmt->execute([$thread_id, $_SESSION['user_id']]);
$thread = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$thread) {
    die("Thread not found or you don't have permission to edit.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("UPDATE threads SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
        if ($stmt->execute([$title, $content, $thread_id])) {
            header("Location: thread.php?id=$thread_id");
            exit;
        } else {
            echo "Failed to update the thread.";
        }
    } else {
        echo "Title and content cannot be empty.";
    }
}
?>

<h1>Edit Thread</h1>
<form method="POST">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($thread['title'] ?? '') ?>" required>
    <label>Content:</label>
    <textarea name="content" required><?= htmlspecialchars($thread['content'] ?? '') ?></textarea>
    <button type="submit">Save Changes</button>
</form>

