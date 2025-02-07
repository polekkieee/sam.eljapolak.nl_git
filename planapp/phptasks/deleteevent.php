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

if (!$data || !isset($data["day"], $data["month"], $data["year"], $data["title"])) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// Prepare delete query
$sql = "DELETE FROM events 
        WHERE user_id = :user_id 
        AND day = :day 
        AND month = :month 
        AND year = :year 
        AND title = :title";

$stmt = $conn->prepare($sql);
$stmt->bindParam(":user_id", $user_id);
$stmt->bindParam(":day", $data["day"]);
$stmt->bindParam(":month", $data["month"]);
$stmt->bindParam(":year", $data["year"]);
$stmt->bindParam(":title", $data["title"]);

try {
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Event deleted successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["status" => "error", "message" => "Event not found or already deleted"]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
