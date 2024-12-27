<?php
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// Gunakan parameter user_id dari URL jika ada, jika tidak gunakan user yang sedang login
$user_id = $_GET['user_id'] ?? $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  die("User not found.");
}
?>

<h1>Profile</h1>
<p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
<p><strong>Member since:</strong> <?= $user['created_at'] ?></p>

<!-- Tampilkan tombol logout hanya jika user adalah user yang sedang login -->
<?php if ($user['id'] == $_SESSION['user_id']): ?>
  <a href="logout.php">Logout</a>
<?php endif; ?>
