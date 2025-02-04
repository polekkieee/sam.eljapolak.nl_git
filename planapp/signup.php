<?php
session_start();
require_once "../configurationsettings_sweetplans.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - SweetPlans</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="loginbody">
        <div class="login-container">
            <h2>Sign up - <strong>SweetPlans</strong></h2>
            <form method="post">
                <input type="text" name="_name" placeholder="Name" required>
                <input type="email" name="_email" placeholder="Email" required>
                <input type="password" name="_password" placeholder="Password" required>
                <input type="submit" value="Sign up" name="save">
                <a class="signupbutton" href="login">Log in</a>
            </form>

            <?php
            if (isset($_POST["save"])) {
                $_name = $_POST["_name"];
                $_email = $_POST["_email"];
                $_password = $_POST["_password"];

                // INSERT INTO `users` (`name`, `email`, `password`) VALUES ('testname', 'test@mail.com', 'test123');
                try {
                    $sql = "INSERT INTO `users`(`name`, `email`, `password`)
                    VALUES (:_name, :_email, :_password)";
                    $stmt = $conn->prepare($sql);

                    $_passworde = md5($_password);
                    $stmt->bindParam('_name', $_name);
                    $stmt->bindParam('_email', $_email);
                    $stmt->bindParam('_password', $_passworde);

                    echo "<p>Registered. <a href='login'>Login</a></p>";

                    // insert a row
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage() . "<br>";
                }
            }
            ?>

        </div>



    </div>
</body>

</html>