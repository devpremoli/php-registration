<?php

session_start();

$loginFailed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'connect.php';

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = mysqli_prepare($con, "SELECT password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashedPassword);

        if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashedPassword)) {
                // Login success
                $_SESSION['username'] = $username;
                header("Location: home.php");
                exit();
            } else {
                $loginFailed = true;
            }
        } else {
            $loginFailed = true;
        }

        mysqli_stmt_close($stmt);
    } else {
        $loginFailed = true;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
</head>

<body>

    <?php if ($_GET['registered'] ?? '' === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Registration complete. Please log in.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($loginFailed): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Login failed.</strong> Invalid username or password.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h1 class="text-center">Login</h1>
    <div class="container mt-5">
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <a href="signup.php" class="btn btn-primary w-100 mt-1">Signup</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>