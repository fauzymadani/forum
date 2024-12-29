<?php
include 'includes/config.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Check if username exists
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  if ($stmt->rowCount() > 0) {
    echo "Username already exists.";
  } else {
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "Registration successful. <a href='login.php'>Login here</a>";
  }
}
?>

<link rel="stylesheet" href="assets/gruvbox_dark.css">
<div class="content">
  <h1>Register</h1>
  <form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Register</button>
  </form>
</div>
