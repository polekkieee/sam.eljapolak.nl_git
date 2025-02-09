<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Landing Page</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Viaoda+Libre&display=swap" rel="stylesheet">
</head>

<body>
  <div id="light" style="--light-position-y: -100px; --light-position-x: -100px"></div>

  <div class="switch">
    <input type="checkbox" id="switchToggle">
    <label for="switchToggle" class="switch-label"></label>
  </div>
  
  <div class="centerbox">
    <div class="container cinzel-decorative-black">
      <video autoplay muted loop id="video-background">
        <source src="earth.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="content">
        LAUNCHING <br> SOON
      </div>
      <button class="signup cinzel-decorative-regular ">click here</button>
    </div>
  </div>

  <script>

    // Light behind cursor
    var light = document.getElementById('light');
    var isTracking = false;

    document.documentElement.addEventListener('mouseenter', function() {
      isTracking = true;
    });

    document.documentElement.addEventListener('mouseleave', function() {
      isTracking = false;
      light.style.setProperty('--light-position-y', '-100px');
      light.style.setProperty('--light-position-x', '-100px');
    });

    document.documentElement.addEventListener('mousemove', function handleMouseMove(event) {
      if (isTracking) {
        light.style.setProperty('--light-position-y', (event.clientY) + 'px');
        light.style.setProperty('--light-position-x', (event.clientX) + 'px');
      }
    });


    // Switch button
    const switchToggle = document.getElementById('switchToggle');
    const videoSource = document.querySelector('#video-background source');
    const videoElement = document.getElementById('video-background');

    // Add change event listener to the toggle switch
    switchToggle.addEventListener('change', () => {
      if (switchToggle.checked) {
        videoSource.src = 'moon.mp4';
      } else {
        videoSource.src = 'earth.mp4';
      }

      // Reload the video with the new source
      videoElement.load();
      videoElement.play();
    });
  </script>


</body>

</html>