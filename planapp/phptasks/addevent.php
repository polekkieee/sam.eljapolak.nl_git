<?php
session_start();
require_once '../../configurationsettings_sweetplans.php';

$user_id = $_SESSION["userId"];

$sql = "INSERT INTO events (user_id, day, month, year, title, time_from, time_to) VALUES(
                                :user_id,
                                :day,
                                :month,
                                :year,
                                :title,
                                :time_from,
                                :time_to
                                )";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":user_id", $user_id);
$stmt->bindParam(":title", $_POST["title"]);
$stmt->bindParam(":day", $_POST["day"]);
$stmt->bindParam(":month", $_POST["month"]);
$stmt->bindParam(":year", $_POST["year"]);
$stmt->bindParam(":time_from", $_POST["time_from"]);
$stmt->bindParam(":time_to", $_POST["time_to"]);
$stmt->execute();