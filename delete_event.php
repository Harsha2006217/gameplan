<?php
require 'functions.php';
if (!isLoggedIn()) header("Location: login.php");
$id = $_GET['id'] ?? 0;
deleteEvent($id);
header("Location: index.php");
exit;
?>