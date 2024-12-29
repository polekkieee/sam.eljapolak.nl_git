<?php
$servername = "localhost:3306";
$username = "sampolak";
$password = "Zq9C6e&ab0AJ1g6U";
$dbname = "webshop";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

/*

    try{
        $sql = "INSERT INTO `_table`(`_column`)
                VALUES (:_value)"; 

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':_value', $_var);
        
        // insert a row
        $stmt->execute();
    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage() . "<br>";
    }

*/
