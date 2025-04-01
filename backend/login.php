<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if ($username && $password) {
    $stmt = mysqli_prepare($con, "SELECT password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hashedPassword);

    if (mysqli_stmt_fetch($stmt) && password_verify($password, $hashedPassword)) {
        $_SESSION['username'] = $username;
        echo json_encode(['success' => true, 'username' => $username]);
    } else {
        echo json_encode(['error' => 'Invalid username or password']);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Missing username or password']);
}
