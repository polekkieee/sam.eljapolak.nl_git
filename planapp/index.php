<?php
session_start();
require_once "../configurationsettings_sweetplans.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetPlans</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="width-33"><img class="logoimg" src="img/logo.svg" alt="SweetPlans Logo"></div>
        <div class="width-33">
            <h1><strong>SweetPlans</strong></h1>
        </div>
        <div class="account-buttons">
            <div><a class="loginbutton" href="login">Log In</a></div>
            <div><a class="signupbutton" href="signup">Sign Up</a></div>
        </div>
        <div class="burgermenu nav-container">
            <input class="checkbox" type="checkbox" name="" id="" />
            <div class="hamburger-lines">
                <span class="line line1"></span>
                <span class="line line2"></span>
                <span class="line line3"></span>
            </div>
            <div class="menu-items">
                <li><a class="loginbutton" href="login">Log In</a></li>
                <li><a class="signupbutton" href="signup">Sign Up</a></li>
            </div>
        </div>
    </nav>
    <div class="box">
        <header>

            <div class="box-1">
                <div class="welcomemsg">
                    <h2>Welcome to <br><strong>SweetPlans</strong></h2>
                    <p>Plan your events together, <br> with ease.
                    </p>
                </div>
                <div class="demo-wrapper">
                    <div class="demo">

                    </div>
                </div>
            </div>

        </header>
    </div>

    <script>
        document.querySelector('.menu-items').addEventListener('click', function() {
            setTimeout(function() {
                document.querySelector('.checkbox').checked = false;
            }, 200);
        });
    </script>

</body>

</html>

<?php
