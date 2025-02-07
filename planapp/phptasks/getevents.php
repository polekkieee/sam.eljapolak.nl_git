<?php
session_start();
require_once "../../configurationsettings_sweetplans.php";

$user_id = $_SESSION["userId"];

// Fetch events for the logged-in user
$sql = "SELECT * FROM events WHERE user_id = :user_id ORDER BY year, month, day, time_from";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();

$events = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dateKey = "{$row['day']}-{$row['month']}-{$row['year']}";

    if (!isset($events[$dateKey])) {
    $events[$dateKey] = [
        "day" => $row["day"],
        "month" => $row["month"],
        "year" => $row["year"],
        "events" => [],
    ];
    }

    $events[$dateKey]["events"][] = [
    "title" => $row["title"],
    "time" => $row["time_from"] . " - " . $row["time_to"],
    ];
}
// Return the events as a JSON response
header('Content-Type: application/json');
echo json_encode(array_values($events)); // Convert associative array to indexed array
exit();