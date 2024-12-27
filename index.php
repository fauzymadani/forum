<?php
include 'includes/config.php';
include 'includes/header.php';

// Fetch threads
$query = $pdo->query("SELECT threads.*, users.username FROM threads JOIN users ON threads.user_id = users.id ORDER BY created_at DESC");
$threads = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Forum Threads</h1>
<a href="create_thread.php"><button>Create New Thread</button></a>
<ul class="threads-list">
  <?php foreach ($threads as $thread): ?>
    <li class="threads-link">
      <a href="thread.php?id=<?= $thread['id'] ?>"><?= htmlspecialchars($thread['title']) ?></a>
      <p>by <?= htmlspecialchars($thread['username']) ?> at <?= $thread['created_at'] ?></p>
    </li>
  <?php endforeach; ?>
</ul>
