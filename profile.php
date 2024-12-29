<?php
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$user_id = $_GET['user_id'] ?? $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  die("User not found.");
}
?>

<style>
  .wrapper {
    padding: 10px;
    margin: 10px;
    border: 1px solid #ebdbb2;
    border-radius: 5px;
  }
</style>
<div class="wrapper">
  <div class="header-wrapper">
    <h1>Profile</h1>
  </div>
  <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
  <p><strong>Member since:</strong> <?= $user['created_at'] ?></p>
  <p><strong>Last active:</strong> <?= htmlspecialchars($user['last_active'] ?? 'Never') ?></p>

  <?php if ($user['id'] == $_SESSION['user_id']): ?>
    <a href="logout.php"><button>Logout</button></a>
  <?php endif; ?>
</div>
