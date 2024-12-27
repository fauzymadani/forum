<?php
include 'includes/config.php';
include 'includes/header.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$comment_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil komentar berdasarkan ID dan pastikan pengguna adalah pemiliknya
$stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ? AND user_id = ?");
$stmt->execute([$comment_id, $user_id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    die("Comment not found or you don't have permission to edit.");
}

// Proses jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Hapus komentar
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
        header("Location: thread.php?id=" . $comment['thread_id']);
        exit;
    } elseif (isset($_POST['update'])) {
        // Perbarui komentar
        $content = $_POST['content'] ?? '';

        if (!empty($content)) {
            $stmt = $pdo->prepare("UPDATE comments SET content = ? WHERE id = ?");
            $stmt->execute([$content, $comment_id]);
            header("Location: thread.php?id=" . $comment['thread_id']);
            exit;
        } else {
            echo "Content cannot be empty.";
        }
    }
}
?>

<h1>Edit Comment</h1>
<form method="POST">
    <label>Content:</label>
    <textarea name="content" required><?= htmlspecialchars($comment['content']) ?></textarea>
    <button type="submit" name="update">Save Changes</button>
    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
</form>

