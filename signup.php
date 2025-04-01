<?php
$userExist = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'connect.php';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = mysqli_prepare($con, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $userExist = true;
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($con, "INSERT INTO users (username, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: login.php?registered=success");
                exit();
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Sign Up</title>
</head>

<body>
    <?php if ($userExist): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>User already exists.</strong> Try another username.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h1 class="text-center">Signup</h1>
    <div class="container mt-5">
        <form action="sign.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Signup</button>
        </form>

        <a href="login.php" class="btn btn-primary w-100 mt-1">Login</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>