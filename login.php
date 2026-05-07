<?php
session_start();
include 'connection.php';

if (isset($_SESSION['user'])) {
    // Already logged in — send to the right place
    header("Location: " . ($_SESSION['is_admin'] ? "Dashboard.php" : "userdashboard.php"));
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user']      = $user['email'];
        $_SESSION['user_name'] = $user['fname'] . ' ' . $user['lname'];
        $_SESSION['is_admin']  = (int)$user['is_admin'];

        if ($user['is_admin'] == 1) {
            header("Location: Dashboard.php");
        } else {
            header("Location: userdashboard.php");
        }
        exit;
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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-box">
        <h2>Welcome back</h2>
        <p>Sign in to your account</p>

        <?php if ($error): ?>
        <div class="alert alert-err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="you@example.com"
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