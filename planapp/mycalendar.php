<?php
session_start();
require_once "../configurationsettings_sweetplans.php"; // Include your database configuration

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the action from the AJAX request
if (!isset($_POST["action"])) {
    die(json_encode(["status" => "error", "message" => "Action not specified"]));
}
$action = $_POST["action"];

// Get the logged-in user's ID from the session
if (!isset($_SESSION["id"])) {
    die(json_encode(["status" => "error", "message" => "User not logged in"]));
}
$user_id = $_SESSION["id"];

try {
    // Handle the "getEvents" action
    if ($action == "getEvents") {
        // Fetch events for the logged-in user
        $sql = "SELECT * FROM events WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Store the events in an array
        $events = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $events[] = [
                "day" => $row["day"],
                "month" => $row["month"],
                "year" => $row["year"],
                "events" => [
                    [
                        "title" => $row["title"],
                        "time" => $row["time_from"] . " - " . $row["time_to"],
                    ],
                ],
            ];
        }

        // Return the events as JSON
        echo json_encode($events);
    }

    // Handle the "addEvent" action
    elseif ($action == "addEvent") {
        // Validate required fields
        if (
            !isset($_POST["day"]) ||
            !isset($_POST["month"]) ||
            !isset($_POST["year"]) ||
            !isset($_POST["title"]) ||
            !isset($_POST["time_from"]) ||
            !isset($_POST["time_to"])
        ) {
            die(json_encode(["status" => "error", "message" => "Missing required fields"]));
        }

        // Get the event data from the AJAX request
        $day = $_POST["day"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $title = $_POST["title"];
        $time_from = $_POST["time_from"];
        $time_to = $_POST["time_to"];

        // Insert the event into the database
        $sql = "INSERT INTO events (user_id, day, month, year, title, time_from, time_to) 
                VALUES (:user_id, :day, :month, :year, :title, :time_from, :time_to)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":day", $day, PDO::PARAM_INT);
        $stmt->bindParam(":month", $month, PDO::PARAM_INT);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":time_from", $time_from, PDO::PARAM_STR);
        $stmt->bindParam(":time_to", $time_to, PDO::PARAM_STR);

        // Check if the event was added successfully
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add event"]);
        }
    }

    // Handle the "deleteEvent" action
    elseif ($action == "deleteEvent") {
        // Validate required fields
        if (
            !isset($_POST["day"]) ||
            !isset($_POST["month"]) ||
            !isset($_POST["year"]) ||
            !isset($_POST["title"])
        ) {
            die(json_encode(["status" => "error", "message" => "Missing required fields"]));
        }

        // Get the event data from the AJAX request
        $day = $_POST["day"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $title = $_POST["title"];

        // Delete the event from the database
        $sql = "DELETE FROM events 
                WHERE user_id = :user_id 
                AND day = :day 
                AND month = :month 
                AND year = :year 
                AND title = :title";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":day", $day, PDO::PARAM_INT);
        $stmt->bindParam(":month", $month, PDO::PARAM_INT);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);

        // Check if the event was deleted successfully
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete event"]);
        }
    }

    // Invalid action
    else {
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
    }
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="Stay organized with our user-friendly Calendar featuring events, reminders, and a customizable interface. Built with HTML, CSS, and JavaScript. Start scheduling today!"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
      integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="appstyle.css" />
    <title>My Calendar - <?php echo $_SESSION["name"] ?></title>
  </head>
  <body>
    <div class="container">
      <div class="left">
        <div class="calendar">
          <div class="month">
            <i class="fas fa-angle-left prev"></i>
            <div class="date">december 2015</div>
            <i class="fas fa-angle-right next"></i>
          </div>
          <div class="weekdays">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
          </div>
          <div class="days"></div>
          <div class="goto-today">
            <div class="goto">
              <input type="text" placeholder="mm/yyyy" class="date-input" />
              <button class="goto-btn">Go</button>
            </div>
            <button class="today-btn">Today</button>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="today-date">
          <div class="event-day">wed</div>
          <div class="event-date">12th december 2022</div>
        </div>
        <div class="events"></div>
        <div class="add-event-wrapper">
          <div class="add-event-header">
            <div class="title">Add Event</div>
            <i class="fas fa-times close"></i>
          </div>
          <div class="add-event-body">
            <div class="add-event-input">
              <input type="text" placeholder="Event Name" class="event-name" />
            </div>
            <div class="add-event-input">
              <input
                type="text"
                placeholder="Event Time From"
                class="event-time-from"
              />
            </div>
            <div class="add-event-input">
              <input
                type="text"
                placeholder="Event Time To"
                class="event-time-to"
              />
            </div>
          </div>
          <div class="add-event-footer">
            <button class="add-event-btn">Add Event</button>
          </div>
        </div>
      </div>
      <button class="add-event">
        <i class="fas fa-plus"></i>
      </button>
    </div>

    <script src="appscript.js"></script>
  </body>
</html>