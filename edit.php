<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header("Location: Dashboard.php"); exit; }

$id    = (int)$_GET['id'];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname  = trim($_POST['fname']);
    $lname  = trim($_POST['lname']);
    $email  = trim($_POST['email']);
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("UPDATE students SET fname=?, lname=?, email=?, gender=? WHERE id=?");
    $stmt->bind_param("ssssi", $fname, $lname, $email, $gender, $id);
    $stmt->execute() ? header("Location: Dashboard.php") && exit : $error = "Update failed.";
}

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) { header("Location: Dashboard.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="logo">Admin<span>Panel</span></div>
        <nav>
            <a href="Dashboard.php">Dashboard</a>
            <a href="signup.php">Add User</a>
        </nav>
        <div class="logout"><a href="logout.php">Logout</a></div>
    </aside>

    <div class="main">
        <div class="topbar">
            <h1>Edit User</h1>
            <span class="user"><?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['user']) ?></span>
        </div>

        <div class="content">
            <div class="form-card">
                <h2>Editing: <?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?></h2>

                <?php if ($error): ?>
                <div class="alert alert-err"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="two-col">
                        <div class="field">
                            <label>First name</label>
                            <input type="text" name="fname" value="<?= htmlspecialchars($row['fname']) ?>" required>
                        </div>
                        <div class="field">
                            <label>Last name</label>
                            <input type="text" name="lname" value="<?= htmlspecialchars($row['lname']) ?>" required>
                        </div>
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                    </div>

                    <div class="field">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="male"   <?= $row['gender'] === 'male'   ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $row['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other"  <?= $row['gender'] === 'other'  ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-btns">
                        <button type="submit" class="btn btn-green">Save</button>
                        <a href="Dashboard.php" class="btn btn-gray">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>