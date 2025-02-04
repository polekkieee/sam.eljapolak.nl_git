<?php
session_start();
// require_once "../configurationsettings_sweetplans.php";
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

    <?php
    if (isset($_SESSION["loggedin"])) {
    ?>

        <nav id="navbar">
            <div class="width-33"><img class="logoimg" id="logoimg" src="img/logo.svg" alt="SweetPlans Logo"></div>
            <div class="width-33">
                <h1><strong>SweetPlans</strong></h1>
            </div>
            <div class="account-buttons">
                <div><a class="loginbutton" href="profile">Profile</a></div>
                <div><a class="signupbutton" href="index">My calendar</a></div>
            </div>
            <div class="burgermenu nav-container">
                <input class="checkbox" type="checkbox" name="" id="" />
                <div class="hamburger-lines">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </div>
                <div class="menu-items">
                    <li><a class="loginbutton" href="profile">Profile</a></li>
                    <li><a class="signupbutton" href="index">My calendar</a></li>
                </div>
            </div>
        </nav>
        <div class="box">
            <header>

                <div class="box-1">
                    <div class="welcomemsg">
                        <h2>Welcome <br><strong><?php echo $_SESSION["name"] ?></strong></h2>
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

    <?php
    } else {
    ?>

        <header>
            <!-- Static navbar - always visible at the top -->
            <nav class="static">
                <div class="width-33">
                    <img class="logoimg" id="logoimg" src="img/logo.svg" alt="SweetPlans Logo">
                </div>
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

            <!-- Shrunken navbar - appears on scroll -->
            <nav id="navbar" class="shrink">
                <div class="width-33">
                    <img class="logoimg" id="logoimg" src="img/logo.svg" alt="SweetPlans Logo">
                </div>
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
        </header>

        <!-- Content below the navbar -->
        <div class="box">
            <header>
                <div class="box-1">
                    <div class="welcomemsg">
                        <h2>Welcome to <br><strong>SweetPlans</strong></h2>
                        <p>Plan your events together, <br> with ease.</p>
                    </div>
                    <div class="demo-wrapper">
                        <div class="demo">
                            <img src="img/demo.png" alt="Demo image">
                        </div>
                    </div>
                </div>
            </header>
        </div>

    <?php
    }
    ?>

    <script>
        document.querySelector('.menu-items').addEventListener('click', function() {
            setTimeout(function() {
                document.querySelector('.checkbox').checked = false;
            }, 200);
        });

        const navbar = document.getElementById("navbar");

        window.addEventListener("scroll", () => {
            if (window.scrollY >= 140) {
                // Add the 'visible' class to show the shrunk navbar
                navbar.classList.add("visible");
            } else {
                // Add the 'slideUp' class to hide the shrunk navbar
                navbar.classList.remove("visible");
            }
        });
    </script>

</body>

</html>

<?php
