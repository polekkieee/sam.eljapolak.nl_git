<?
$servername = "localhost:3306";
$username = "sampolak";
$password = "Zq9C6e&ab0AJ1g6U";
$dbname = "webshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else{
    echo("connected succesfully");
}
