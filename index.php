<?php
include 'includes/config.php';
include 'includes/header.php';

// Fetch threads
$query = $pdo->query("SELECT threads.*, users.username FROM threads JOIN users ON threads.user_id = users.id ORDER BY created_at DESC");
$threads = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="assets/style.css">
<div class="content">
  <div class="content-header">
    <h1>Forum Threads</h1>
    <a href="create_thread.php"><button>Create New Thread</button></a>
  </div>
  <div class="content-main">
    <table class="threads-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($threads as $thread): ?>
          <tr>
            <td><a href="thread.php?id=<?= $thread['id'] ?>"><?= htmlspecialchars($thread['title']) ?></a></td>
            <td><?= htmlspecialchars($thread['username']) ?></td>
            <td><?= $thread['created_at'] ?></td>
            <td><a href="thread.php?id=<?= $thread['id'] ?>"><button>View</button></a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
