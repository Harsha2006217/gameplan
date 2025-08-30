<?php
require 'functions.php';
if (!isLoggedIn()) header("Location: login.php");
$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
global $pdo;
$stmt = $pdo->prepare("DELETE FROM Schedules WHERE schedule_id = :id AND user_id = :user");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':user', $user_id);
$stmt->execute();
header("Location: index.php");
exit;
?>