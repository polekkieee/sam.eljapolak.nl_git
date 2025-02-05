<?php
session_start();
require_once "../configurationsettings_sweetplans.php"; // Include your database configuration

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debugging: Log the received POST data
error_log("Received POST data: " . print_r($_POST, true));

// Get the action from the AJAX request
if (!isset($_POST["action"])) {
    die(json_encode(["status" => "error", "message" => "Action not specified"]));
}
$action = $_POST["action"];

// Get the logged-in user's ID from the session
if (!isset($_SESSION["userId"])) {
    die(json_encode(["status" => "error", "message" => "User not logged in"]));
}
$user_id = $_SESSION["userId"];

try {
    if ($action == "getEvents") {
        // Fetch events for the logged-in user
        $sql = "SELECT * FROM events WHERE user_id = :user_id ORDER BY year, month, day, time_from";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $events = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dateKey = "{$row['year']}-{$row['month']}-{$row['day']}";

            if (!isset($events[$dateKey])) {
                $events[$dateKey] = [
                    "day" => $row["day"],
                    "month" => $row["month"],
                    "year" => $row["year"],
                    "events" => []
                ];
            }

            $events[$dateKey]["events"][] = [
                "title" => $row["title"],
                "time" => $row["time_from"] . " - " . $row["time_to"]
            ];
        }

        // Re-index the array (convert associative keys to numerical indices)
        echo json_encode(array_values($events));
    }

} catch (PDOException $e) {
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