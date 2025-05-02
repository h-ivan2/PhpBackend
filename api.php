<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
$host = 'localhost';
$db = 'student_db';
$user = 'root'; // Change according to your setup
$pass = 'IVAN24022010@';

// Change according to your setup
// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get the HTTP method$method = $_SERVER['REQUEST_METHOD'];
// Switch based on the HTTP method
switch ($method) {
case 'GET':
if (isset($_GET['id'])) {
// Read a single user
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM students WHERE id = $id");
$user = $result->fetch_assoc();
echo json_encode($user);
} else {
// Read all users
$result = $conn->query("SELECT * FROM students");
$users = [];
while ($row = $result->fetch_assoc()) {
$users[] = $row;
}
echo json_encode($users);
}
break;
case 'POST':// Create a new user
$data = json_decode(file_get_contents("php://input"),
true);

$fname =$conn->real_escape_string($data['fname']);
$lname =$conn->real_escape_string($data['lname']);
$email = $conn->real_escape_string($data['email']);
$gender =$conn->real_escape_string($data['gender']);


$conn->query("INSERT INTO students (fname, lname,email,gender) VALUES ('$fname', '$lname','$email','$gender')");
echo json_encode(["message" => "User created"]);
break;
case 'PUT':
// Update an existing user
$data = json_decode(file_get_contents("php://input"),
true);
$id = $data['id'];
$fname =$conn->real_escape_string($data['fname']);
$lname =$conn->real_escape_string($data['lname']);
$email = $conn->real_escape_string($data['email']);
$gender =$conn->real_escape_string($data['gender']);

$conn->query("UPDATE students SET name='$name',
email='$email' WHERE id=$id");
echo json_encode(["message" => "User updated"]);
break;
case 'DELETE':
// Delete a user
$id = $_GET['id'];$conn->query("DELETE FROM students WHERE id=$id");
echo json_encode(["message" => "User deleted"]);
break;
default:
echo json_encode(["error" => "Invalid request method"]);
break;
}
$conn->close();
?>