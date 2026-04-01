<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

$result   = $conn->query("SELECT * FROM students ORDER BY id ASC");
$students = $result->fetch_all(MYSQLI_ASSOC);
$total    = count($students);
$males    = count(array_filter($students, fn($s) => strtolower($s['gender']) === 'male'));
$females  = count(array_filter($students, fn($s) => strtolower($s['gender']) === 'female'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="logo">Admin<span>Panel</span></div>
        <nav>
            <a href="Dashboard.php" class="active">Dashboard</a>
            <a href="./signup.php">Add User</a>
        </nav>
        <div class="logout"><a href="./logout.php">Logout</a></div>
    </aside>

    <div class="main">
        <div class="topbar">
            <h1>Users</h1>
            <span class="user"><?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['user']) ?></span>
        </div>

        <div class="content">

            <div class="stats">
                <div class="stat">
                    <div class="num"><?= $total ?></div>
                    <div class="lbl">Total Users</div>
                </div>
                <div class="stat green">
                    <div class="num"><?= $males ?></div>
                    <div class="lbl">Male</div>
                </div>
                <div class="stat red">
                    <div class="num"><?= $females ?></div>
                    <div class="lbl">Female</div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <h2>All Users (<?= $total ?>)</h2>
                    <a href="./signup.php" class="btn btn-blue btn-sm">+ Add User</a>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['fname']) ?></td>
                                <td><?= htmlspecialchars($row['lname']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= strtolower($row['gender']) ?>">
                                        <?= ucfirst(htmlspecialchars($row['gender'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-blue btn-sm">Edit</a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-red btn-sm"
                                           onclick="return confirm('Delete <?= htmlspecialchars(addslashes($row['fname'])) ?>?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html><!-- refactor: improve session handling on Dashboard -->
