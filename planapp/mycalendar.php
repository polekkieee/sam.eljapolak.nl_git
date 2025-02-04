<?php
session_start();
require_once "../configurationsettings_sweetplans.php";

if (!isset($_SESSION["loggedin"])) {
    header("location: login");
    exit;
}

$user_id = $_SESSION["id"];

// Function to get events from the database
function getEvents($user_id, $conn) {
    $sql = "SELECT * FROM events WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    return $events;
}

// Function to add an event to the database
function addEvent($user_id, $day, $month, $year, $title, $time_from, $time_to, $conn) {
    $sql = "INSERT INTO events (user_id, day, month, year, title, time_from, time_to) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiisss", $user_id, $day, $month, $year, $title, $time_from, $time_to);
    return $stmt->execute();
}

// Function to delete an event from the database
function deleteEvent($user_id, $day, $month, $year, $title, $conn) {
    $sql = "DELETE FROM events WHERE user_id = ? AND day = ? AND month = ? AND year = ? AND title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiis", $user_id, $day, $month, $year, $title);
    return $stmt->execute();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];
    if ($action == "getEvents") {
        echo json_encode(getEvents($user_id, $conn));
    } elseif ($action == "addEvent") {
        $day = $_POST["day"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $title = $_POST["title"];
        $time_from = $_POST["time_from"];
        $time_to = $_POST["time_to"];
        if (addEvent($user_id, $day, $month, $year, $title, $time_from, $time_to, $conn)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    } elseif ($action == "deleteEvent") {
        $day = $_POST["day"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $title = $_POST["title"];
        if (deleteEvent($user_id, $day, $month, $year, $title, $conn)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
    exit;
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