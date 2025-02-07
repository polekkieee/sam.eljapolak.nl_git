<?php
session_start();
require_once '../../configurationsettings_sweetplans.php';

// Check if user is logged in
if (!isset($_SESSION["userId"])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["status" => "error", "message" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION["userId"];

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit;
}

// Prepare and execute query
$sql = "INSERT INTO events (user_id, day, month, year, title, time_from, time_to) 
        VALUES (:user_id, :day, :month, :year, :title, :time_from, :time_to)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(":user_id", $user_id);
$stmt->bindParam(":title", $data["title"]);
$stmt->bindParam(":day", $data["day"]);
$stmt->bindParam(":month", $data["month"]);
$stmt->bindParam(":year", $data["year"]);
$stmt->bindParam(":time_from", $data["time_from"]);
$stmt->bindParam(":time_to", $data["time_to"]);

try {
    $stmt->execute();
    echo json_encode(["status" => "success", "message" => "Event added successfully"]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
