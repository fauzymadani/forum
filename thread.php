<?php
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    die("Thread not found.");
}

$thread_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT threads.*, users.username FROM threads JOIN users ON threads.user_id = users.id WHERE threads.id = ?");
$stmt->execute([$thread_id]);
$thread = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$thread) {
    die("Thread not found.");
}

// Fetch comments
$comments = $pdo->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE thread_id = ? ORDER BY created_at ASC");
$comments->execute([$thread_id]);
$comments = $comments->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_thread']) && $_POST['delete_thread'] == $thread_id && $_SESSION['user_id'] === $thread['user_id']) {
        // Delete the thread
        $pdo->prepare("DELETE FROM threads WHERE id = ?")->execute([$thread_id]);
        $pdo->prepare("DELETE FROM comments WHERE thread_id = ?")->execute([$thread_id]);
        header('Location: index.php');
        exit;
    }

    if (isset($_POST['delete_comment'])) {
        $comment_id = $_POST['delete_comment'];
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($comment && $comment['user_id'] === $_SESSION['user_id']) {
            // Delete the comment
            $pdo->prepare("DELETE FROM comments WHERE id = ?")->execute([$comment_id]);
            header("Location: thread.php?id=$thread_id");
            exit;
        }
    }
}
?>

<h1><?= htmlspecialchars($thread['title']) ?></h1>
<p><?= htmlspecialchars($thread['content']) ?></p>
<p>by <?= htmlspecialchars($thread['username']) ?> at <?= $thread['created_at'] ?></p>
<p><strong>Posted by:</strong> <a href="profile.php?user_id=<?= $thread['user_id'] ?>"><?= htmlspecialchars($thread['username']) ?></a></p>

<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $thread['user_id']): ?>
    <form method="POST">
        <a href="edit_thread.php?id=<?= $thread_id ?>">Edit</a>
        <button type="submit" name="delete_thread" value="<?= $thread_id ?>" onclick="return confirm('Are you sure you want to delete this thread?')">Delete</button>
    </form>
<?php endif; ?>

<h2>Comments</h2>
<ul>
  <?php foreach ($comments as $comment): ?>
    <div class="comment">
        <p><strong><?= htmlspecialchars($comment['username']) ?>:</strong></p>
        <p><?= htmlspecialchars($comment['content']) ?></p>
        <p><small>Posted on <?= $comment['created_at'] ?></small></p>
        
        <!-- show edit and delete button if the user is the owner -->
        <?php if ($comment['user_id'] === $_SESSION['user_id']): ?>
            <a href="edit_comment.php?id=<?= $comment['id'] ?>">Edit</a>
            <form method="POST" action="edit_comment.php?id=<?= $comment['id'] ?>" style="display:inline;">
                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</ul>

<h2>Add a Comment</h2>
<form method="POST" action="add_comment.php">
    <input type="hidden" name="thread_id" value="<?= $thread_id ?>">
    <textarea name="content" required></textarea>
    <button type="submit">Comment</button>
</form>

