<?php
require_once "../configurationsettings_sweetplans.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SweetPlans</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="loginbody">
        <div class="login-container">
            <h2>Login to <strong>SweetPlans</strong></h2>
            <form action="index.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login" name="save">
                <a class="signupbutton" href="signup.php">Sign up</a>
            </form>
        </div>
    </div>
</body>

</html>