<?php
session_start();
require_once "../configurationsettings_sweetplans.php"; // Include your database configuration

if ($_SESSION["loggedin"] !== true) {
  header("location: login.php");
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
    content="Stay organized with our user-friendly Calendar featuring events, reminders, and a customizable interface. Built with HTML, CSS, and JavaScript. Start scheduling today!" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="appstyle.css" />
  <link rel="icon" href="/planapp/favicon.ico">
  <title>My Calendar - <?php echo $_SESSION["name"] ?></title>
</head>

<body>
  <header>
    <nav class="static">
      <div class="width-33">
        <img class="logoimg" id="logoimg" src="img/logo.svg" alt="SweetPlans Logo">
      </div>
      <div class="width-33">
        <h1><strong>SweetPlans</strong></h1>
      </div>
      <div class="burgermenu nav-container">
        <input class="checkbox" type="checkbox" name="" id="" />
        <div class="hamburger-lines">
          <span class="line line1"></span>
          <span class="line line2"></span>
          <span class="line line3"></span>
        </div>
        <div class="menu-items" z-index="100">
          <li><a class="loginbutton" href="index">Home</a></li>
          <li><a class="loginbutton" href="profile">Profile</a></li>
          <li><a class="signupbutton" href="logout">Log Out</a></li>
        </div>
      </div>
    </nav>
  </header>

  <div class="container">
    <div class="left">
      <div class="calendar">
        <div class="month">
          <i class="fas fa-angle-left prev"></i>
          <div class="date">december 2015</div>
          <i class="fas fa-angle-right next"></i>
        </div>
        <div class="weekdays">
          <div>Mon</div>
          <div>Tue</div>
          <div>Wed</div>
          <div>Thu</div>
          <div>Fri</div>
          <div>Sat</div>
          <div>Sun</div>
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
              class="event-time-from" />
          </div>
          <div class="add-event-input">
            <input
              type="text"
              placeholder="Event Time To"
              class="event-time-to" />
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