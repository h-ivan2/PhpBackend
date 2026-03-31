<?php
session_start();
include 'connection.php';

if (isset($_SESSION['user'])) { header("Location: Dashboard.php"); exit; }

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_admin'] != 1) {
            $error = "Access denied. Admins only.";
        } else {
            $_SESSION['user']      = $user['email'];
            $_SESSION['user_name'] = $user['fname'] . ' ' . $user['lname'];
            header("Location: Dashboard.php");
            exit;
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-box">
        <h2>Admin Login</h2>
        <p>Only admins can access this panel</p>

        <?php if ($error): ?>
        <div class="alert alert-err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="admin@example.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-blue btn-full">Login</button>
        </form>
    </div>
</div>
</body>
</html>