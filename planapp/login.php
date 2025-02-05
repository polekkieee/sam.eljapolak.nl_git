<?php
session_start();
require_once "../configurationsettings_sweetplans.php";

$error = false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SweetPlans</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="/planapp/favicon.ico">
</head>

<body>
    <?php
    if (isset($_SESSION["loggedin"])) {
    ?>
        <div class="centerbox">
            <div>
                <a class="signupbutton" href="logout">Log out</a>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="loginbody">
            <div class="login-container">
                <h2>Login - <strong>SweetPlans</strong></h2>
                <form method="post">
                    <input type="email" name="_email" placeholder="Email" required>
                    <input type="password" name="_password" placeholder="Password" required>
                    <input type="submit" value="Login" name="save">
                    <a class="signupbutton" href="signup">Sign up</a>
                </form>

                <?php
                if (isset($_POST["save"])) {
                    $_email = $_POST["_email"];
                    $_password = $_POST["_password"];

                    $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':email', $_email);
                    $_passworde = md5($_password);
                    $stmt->bindParam(':password', $_passworde);
                    $stmt->execute();
                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $result = $stmt->fetch();
                    $rows = $stmt->rowCount();
                    if ($rows == 1) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["email"] = $_email;
                        $_SESSION["name"] = $result["name"];
                        $_SESSION["userId"] = $result["id"];
                        echo "<p class='registered'>Logged in succesfully. Go to <a href='index'>Home</a> or <a href='profile'>Profile</a></p>";
                        // header("location:profile");

                    } else {
                        echo "<p class='registered'>User not found</p>";
                    }
                }
                if ($error) {
                    echo "<p class='registered'>Wrong email and/or password</p>";
                }
                ?>

            </div>
        </div>
    <?php
    }
    ?>

</body>

</html>