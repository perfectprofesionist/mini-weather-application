<?php

// Function to fetch weather data from OpenWeatherMap API
function getWeatherData($city, $apiKey, $type) {
    // API endpoint URL for current weather or forecast
    $url = ($type === 'current') ? 
        "http://api.openweathermap.org/data/2.5/weather?q=$city&units=metric&appid=$apiKey" :
        "http://api.openweathermap.org/data/2.5/forecast?q=$city&units=metric&appid=$apiKey";
    
    // Initialize cURL session
    $curl = curl_init($url);
    
    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return response as a string
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (for simplicity)
    
    // Execute cURL request
    $response = curl_exec($curl);
    
    // Check for cURL errors
    if ($response === false) {
        // Return an error message
        return "Error fetching data: " . curl_error($curl);
    }
    
    // Close cURL session
    curl_close($curl);
    
    // Decode JSON response
    $data = json_decode($response, true);

   
    
    // Check if API request was successful
    if (isset($data['cod']) ) {
        if($data['cod'] == 200) {
            // Return weather data
            return $data;
        } else {
             // Return an error message
            return "Error: Unable to fetch weather data. Please check the city name and try again.";
        }
        
    } else {
        // Return an error message
        return "Error: Unable to fetch weather data. Please check the city name and try again.";
    }
}

// Function to format temperature
function formatTemperature($temperature) {
    return round($temperature, 1) . '°C';
}

// API key for OpenWeatherMap (replace with your own API key)
$apiKey = 'YOUR_OPENWEATHERMAP_API_KEY';

// Initialize variables
$city = '';
$type = 'current'; // Default to current weather
$error = '';
$weatherData = array();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user input city and type from the form
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : 'current';
    
    // Fetch weather data for the specified city and type
    $weatherData = getWeatherData($city, $apiKey, $type);
    
    // Check if there is an error
    if (is_string($weatherData)) {
        $error = $weatherData;
        $weatherData = array(); // Clear weather data in case of error
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast Application</title>
    <style>
        .error { color: red; }

        @import url("https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800,900");
@import url("https://cdn.linearicons.com/free/1.0.0/icon-font.min.css");
body {
  font-family: 'Montserrat', sans-serif;
  background: #112233;
}

.weather-card {
  margin: 60px auto;
  height: 740px;
  width: 450px;
  background: #fff;
  box-shadow: 0 1px 38px rgba(0, 0, 0, 0.15), 0 5px 12px rgba(0, 0, 0, 0.25);
  overflow: hidden;
}
.weather-card .top {
  position: relative;
  height: 570px;
  width: 100%;
  overflow: hidden;
  background: url("https://s-media-cache-ak0.pinimg.com/564x/cf/1e/c4/cf1ec4b0c96e59657a46867a91bb0d1e.jpg") no-repeat;
  background-size: cover;
  background-position: center center;
  text-align: center;
}
.weather-card .top .wrapper {
  padding: 30px;
  position: relative;
  z-index: 1;
}
.weather-card .top .wrapper .mynav {
  height: 20px;
}
.weather-card .top .wrapper .mynav .lnr {
  color: #fff;
  font-size: 20px;
}
.weather-card .top .wrapper .mynav .lnr-chevron-left {
  display: inline-block;
  float: left;
}
.weather-card .top .wrapper .mynav .lnr-cog {
  display: inline-block;
  float: right;
}
.weather-card .top .wrapper .heading {
  margin-top: 20px;
  font-size: 35px;
  font-weight: 400;
  color: #fff;
}
.weather-card .top .wrapper .location {
  margin-top: 20px;
  font-size: 21px;
  font-weight: 400;
  color: #fff;
}
.weather-card .top .wrapper .temp {
  margin-top: 20px;
}
.weather-card .top .wrapper .temp a {
  text-decoration: none;
  color: #fff;
}
.weather-card .top .wrapper .temp a .temp-type {
  font-size: 85px;
}
.weather-card .top .wrapper .temp .temp-value {
  display: inline-block;
  font-size: 85px;
  font-weight: 600;
  color: #fff;
}
.weather-card .top .wrapper .temp .deg {
  display: inline-block;
  font-size: 35px;
  font-weight: 600;
  color: #fff;
  vertical-align: top;
  margin-top: 10px;
}
.weather-card .top:after {
  content: "";
  height: 100%;
  width: 100%;
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.5);
}
.weather-card .bottom {
  padding: 0 30px;
  background: #fff;
}
.weather-card .bottom .wrapper .forecast {
  overflow: hidden;
  margin: 0;
  font-size: 0;
  padding: 0;
  padding-top: 20px;
  max-height: 155px;
}
.weather-card .bottom .wrapper .forecast a {
  text-decoration: none;
  color: #000;
}
.weather-card .bottom .wrapper .forecast .go-up {
  text-align: center;
  display: block;
  font-size: 25px;
  margin-bottom: 10px;
}
.weather-card .bottom .wrapper .forecast li {
  display: block;
  font-size: 25px;
  font-weight: 400;
  color: rgba(0, 0, 0, 0.25);
  line-height: 1em;
  margin-bottom: 30px;
}
.weather-card .bottom .wrapper .forecast li .date {
  display: inline-block;
}
.weather-card .bottom .wrapper .forecast li .condition {
  display: inline-block;
  vertical-align: middle;
  float: right;
  font-size: 25px;
}
.weather-card .bottom .wrapper .forecast li .condition .temp {
  display: inline-block;
  vertical-align: top;
  font-family: 'Montserrat', sans-serif;
  font-size: 20px;
  font-weight: 400;
  padding-top: 2px;
}
.weather-card .bottom .wrapper .forecast li .condition .temp .deg {
  display: inline-block;
  font-size: 10px;
  font-weight: 600;
  margin-left: 3px;
  vertical-align: top;
}
.weather-card .bottom .wrapper .forecast li .condition .temp .temp-type {
  font-size: 20px;
}
.weather-card .bottom .wrapper .forecast li.active {
  color: rgba(0, 0, 0, 0.8);
}
.weather-card.rain .top {
  background: url("http://img.freepik.com/free-vector/girl-with-umbrella_1325-5.jpg?size=338&ext=jpg") no-repeat;
  background-size: cover;
  background-position: center center;
}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    
    



<div class="container">
	<div class="row">
        <div class="col-12 mb-5">
            <h1>Weather Forecast Application</h1>
            <form method="POST" action="">
                <label for="city">Enter City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
                <label for="type">Select Weather Type:</label>
                <select id="type" name="type">
                    <option value="current" <?php if ($type === 'current') echo 'selected'; ?>>Current Weather</option>
                    <option value="forecast" <?php if ($type === 'forecast') echo 'selected'; ?>>5-day Forecast</option>
                </select>
                <button type="submit">Get Weather</button>
            </form>
        </div>
    


        <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
            <?php elseif ($weatherData): ?>
                <?php if ($type === 'current'): ?>

                    <div class="col">
                                        <div class="weather-card rain">
                                            <div class="top"  style="background: url('img/<?php echo str_replace(' ', '-', ($weatherData['weather'][0]['description'])); ?>.png') no-repeat;  background-size: cover; background-position: center center;">
                                                <div class="wrapper">
                                                    <div class="mynav">
                                                        <a href="javascript:;"><span class="lnr lnr-chevron-left"></span></a>
                                                        <a href="javascript:;"><span class="lnr lnr-cog"></span></a>
                                                    </div>
                                                    <h1 class="heading"><?php echo ucfirst($weatherData['weather'][0]['description']); ?></h1>
                                                    <h3 class="location"><?php echo htmlspecialchars($city); ?></h3>
                                                    <div><img src="http://openweathermap.org/img/w/<?php echo $weatherData['weather'][0]['icon']; ?>.png" alt="Weather Icon"></div>
                                                    <p class="temp">
                                                        <span class="temp-value"><?php echo formatTemperature($weatherData['main']['temp']); ?></span>
                                                        
                                                    </p>
                                                    <div>
                                                        <p style="    color: white;font-size: 24px;"><?php echo date('d M \'y', $weatherData['dt']); ?><span style=" width:100%; display:block;   color: white;font-size: 29px;"><?php echo date('h:i A', $weatherData['dt']); ?></span></p>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="bottom">
                                                <div class="wrapper">
                                                    <ul class="forecast">
                                                        <a href="javascript:;"><span class="lnr lnr-chevron-up go-up"></span></a>
                                                        <li class="active">
                                                            <span class="date">Humidity</span>
                                                            <span class="lnr lnr-sun condition">
                                                                <span class="temp"><?php echo $weatherData['main']['humidity']; ?><span class="temp-type">%</span></span>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="date">Wind Speed</span>
                                                            <span class="lnr lnr-cloud condition">
                                                                <span class="temp"><?php echo $weatherData['wind']['speed']; ?><span class="temp-type">m/s</span></span>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                   
                <?php elseif ($type === 'forecast'): ?>
                    <h2>5-day Weather Forecast for <?php echo htmlspecialchars($city); ?>:</h2>
                        <div class="container">
                            <div class="row">
                                <?php foreach ($weatherData['list'] as $forecast): ?>
                                    <?php /* <div class="col-3">
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <p><strong>Date:</strong> <?php echo date('Y-m-d H:i:s', $forecast['dt']); ?></p>
                                                <p><strong>Temperature:</strong> <?php echo formatTemperature($forecast['main']['temp']); ?></p>
                                                <p><strong>Description:</strong> <?php echo ucfirst($forecast['weather'][0]['description']); ?></p>
                                                <img src="http://openweathermap.org/img/w/<?php echo $forecast['weather'][0]['icon']; ?>.png" alt="Weather Icon">
                                                <p><strong>Humidity:</strong> <?php echo $forecast['main']['humidity']; ?>%</p>
                                                <p><strong>Wind Speed:</strong> <?php echo $forecast['wind']['speed']; ?> m/s</p>
                                            </div>
                                        </div>
                                    </div> */?>
                                    <div class="col">
                                        <div class="weather-card rain">
                                            <div class="top"  style="background: url('img/<?php echo str_replace(' ', '-', ($forecast['weather'][0]['description'])); ?>.png') no-repeat;  background-size: cover; background-position: center center;">
                                                <div class="wrapper">
                                                    <div class="mynav">
                                                        <a href="javascript:;"><span class="lnr lnr-chevron-left"></span></a>
                                                        <a href="javascript:;"><span class="lnr lnr-cog"></span></a>
                                                    </div>
                                                    <h1 class="heading"><?php echo ucfirst($forecast['weather'][0]['description']); ?></h1>
                                                    <h3 class="location"><?php echo htmlspecialchars($city); ?></h3>
                                                    <div><img src="http://openweathermap.org/img/w/<?php echo $forecast['weather'][0]['icon']; ?>.png" alt="Weather Icon"></div>
                                                    <p class="temp">
                                                        <span class="temp-value"><?php echo formatTemperature($forecast['main']['temp']); ?></span>
                                                        
                                                    </p>
                                                    <div>
                                                        <p style="    color: white;font-size: 24px;"><?php echo date('d M \'y', $forecast['dt']); ?><span style=" width:100%; display:block;   color: white;font-size: 29px;"><?php echo date('h:i A', $forecast['dt']); ?></span></p>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="bottom">
                                                <div class="wrapper">
                                                    <ul class="forecast">
                                                        <a href="javascript:;"><span class="lnr lnr-chevron-up go-up"></span></a>
                                                        <li class="active">
                                                            <span class="date">Humidity</span>
                                                            <span class="lnr lnr-sun condition">
                                                                <span class="temp"><?php echo $forecast['main']['humidity']; ?><span class="temp-type">%</span></span>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="date">Wind Speed</span>
                                                            <span class="lnr lnr-cloud condition">
                                                                <span class="temp"><?php echo $forecast['wind']['speed']; ?><span class="temp-type">m/s</span></span>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        
                <?php endif; ?>
            <?php endif; ?>
	</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
