<?php
session_start();
if (isset($_SESSION['user_id'])) {
  $stmt = $pdo->prepare("UPDATE users SET last_active = NOW() WHERE id = ?");
  $stmt->execute([$_SESSION['user_id']]);
}

?>

<link rel="stylesheet" href="../assets/style.css">
<div class="navbar">
  <header>
    <nav>
      <a href="index.php"><button class="navbar-button">~/Home</button></a>
      <a href="../commit.php"><button class="navbar-button">~/Changelogs</button></a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php"><button class="navbar-button">~/Profile</button></a>
        <a href="logout.php"><button class="navbar-button">Logout</button></a>
      <?php else: ?>
        <a href="login.php"><button class="navbar-button">Login</button></a>
        <a href="register.php"><button class="navbar-button">Register</button></a>
      <?php endif; ?>
    </nav>
  </header>
</div>
