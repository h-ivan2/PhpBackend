<?php
session_start();
include 'connection.php';

// Admin must be logged in to add users
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $gender   = $_POST['gender'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (empty($fname) || empty($lname) || empty($email) || empty($gender) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO students (fname, lname, email, gender, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fname, $lname, $email, $gender, $hashed);
            $stmt->execute() ? $success = "User created successfully." : $error = "Something went wrong.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="logo">Admin<span>Panel</span></div>
        <nav>
            <a href="Dashboard.php">Dashboard</a>
            <a href="signup.php" class="active">Add User</a>
        </nav>
        <div class="logout"><a href="logout.php">Logout</a></div>
    </aside>

    <div class="main">
        <div class="topbar">
            <h1>Add User</h1>
            <span class="user"><?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['user']) ?></span>
        </div>

        <div class="content">
            <div class="form-card">
                <h2>Create New User</h2>

                <?php if ($error): ?>
                <div class="alert alert-err"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="alert alert-ok">
                    <?= htmlspecialchars($success) ?>
                    <a href="Dashboard.php" style="color:#86efac;font-weight:600;margin-left:8px;">View all users &rarr;</a>
                </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="two-col">
                        <div class="field">
                            <label>First name</label>
                            <input type="text" name="fname" placeholder="John"
                                value="<?= htmlspecialchars($_POST['fname'] ?? '') ?>" required>
                        </div>
                        <div class="field">
                            <label>Last name</label>
                            <input type="text" name="lname" placeholder="Doe"
                                value="<?= htmlspecialchars($_POST['lname'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="user@example.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="field">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">Select gender</option>
                            <option value="male"   <?= (($_POST['gender'] ?? '') === 'male')   ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= (($_POST['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
                            <option value="other"  <?= (($_POST['gender'] ?? '') === 'other')  ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div class="two-col">
                        <div class="field">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Min. 6 chars" required>
                        </div>
                        <div class="field">
                            <label>Confirm password</label>
                            <input type="password" name="confirm" placeholder="Repeat" required>
                        </div>
                    </div>

                    <div class="form-btns">
                        <button type="submit" class="btn btn-blue">Add User</button>
                        <a href="Dashboard.php" class="btn btn-gray">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html><!-- fix: preserve form values on failed signup submission -->
