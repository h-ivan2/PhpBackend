<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: Dashboard.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: Dashboard.php");
exit;
?>

