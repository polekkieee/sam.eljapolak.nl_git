<?php
require_once "../configurationsettings.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="refresh" content="10"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <div class="banner">
        <?php
        include("banner.php");
        ?>
    </div>

    <header>
        <?php

        include("header.php");
        ?>
    </header>

    <nav>

    </nav>

    <main>
        <?php
        include("signup.php");
        ?>
    </main>

    <footer>
        <?php
        include("footer.php");
        ?>
    </footer>

</body>

</html>
