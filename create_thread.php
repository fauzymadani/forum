<?php
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $user_id = $_SESSION['user_id'];
  $image = null;

  if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
    $image = basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $image;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
      $image = $image;
    } else {
      die("Error uploading the image.");
    }
  }

  $stmt = $pdo->prepare("INSERT INTO threads (user_id, title, content, image) VALUES (?, ?, ?, ?)");
  $stmt->execute([$user_id, $title, $content, $image]);

  header('Location: index.php');
  exit;
}
?>

<h1>Create Thread</h1>
<form method="POST" enctype="multipart/form-data">
  <label>Title:</label>
  <input type="text" name="title" required>
  <label>Content:</label>
  <textarea name="content" required></textarea>
  <label>Upload Image:</label>
  <input type="file" name="image" accept="image/*">
  <button type="submit">Save</button>
</form>
