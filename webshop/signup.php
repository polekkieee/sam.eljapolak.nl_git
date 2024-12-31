<?php
require_once "../configurationsettings.php";
?>

<form class="signupform" method="POST">
    <div class="boxsignup">
        <h2>Sign up</h2>
        <input class="inputlogin" type=text name="_name" placeholder="Name" minlength="3" required>
        <input class="inputlogin" type="email" name="_email" placeholder="Email" required>
        <input class="inputlogin" type="password" name="_password" placeholder="Password" minlength="3" required>
        <input class="inputbutton" type="submit" name="save" value="Sign up"><br>
    </div>
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

        echo "<p>Geregistreerd</p>";

        // insert a row
        $stmt->execute();
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage() . "<br>";
    }
}
