<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "as_app");
if ($conn->connect_error) die("Connessione fallita");

$title = trim($_POST['title']);
$content = trim($_POST['content']);
$color = trim($_POST['color']);
$user_id = $_SESSION['user_id'];

if (strlen($title) > 50) {
  die("Il titolo non può superare i 50 caratteri")
}

if (strlen($content) > 200) {
  die("Il content non può superare i 200 caratteri")
}

if ($title && $content && $color) {
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, color) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $content, $color);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: index.php");
exit();
?>
