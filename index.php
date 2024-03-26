<?php 
include('incl.php'); 
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
	<div class="row">
        <div class="col-12 mb-5 mt-5 text-center">
            <h1 class="mb-5">Weather Forecast Application</h1>
            <div class="form-outer">
                <form class="row g-3"  method="POST">
                  <div class="col-auto">
                    <label for="city" class="visually-hidden">Enter City:</label>
                    <input type="text" id="city" name="city" class="form-control" value="<?php echo htmlspecialchars($city); ?>" required Placeholder="City">

                  
                  </div>
                  <div class="col-auto">
                    <label for="type"  class="visually-hidden" >Select Weather Type:</label>
                    <select id="type" name="type" class="form-select">
                        <option value="current" <?php if ($type === 'current') echo 'selected'; ?>>Current Weather</option>
                        <option value="forecast" <?php if ($type === 'forecast') echo 'selected'; ?>>5-day Forecast</option>
                    </select>

                    
                  </div>
                  <div class="col-auto">
                    <button type="submit" class="btn btn-dark mb-3">  Get Weather <span class="lnr lnr-chevron-right"></span></button>
                  </div>
                </form>
            </div>
            
            
        </div>
    


        <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
            <?php elseif ($weatherData): ?>
                <?php if ($type === 'current'): ?>

                    <div class="col">
                      <div class="weather-card rain">
                          <div class="top"  style="background: url('assets/img/<?php echo str_replace(' ', '-', ($weatherData['weather'][0]['description'])); ?>.png') no-repeat;  background-size: cover; background-position: center center;">
                              <div class="wrapper">
                                  <div class="mynav">
                                      
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
                                      <a href="javascript:;"><span style="    line-height: 25px;">&nbsp;</span></a>
                                      <li class="active">
                                          <span class="date">Humidity</span>
                                          <span class="lnr lnr-drop condition">
                                              <span class="temp"><?php echo $weatherData['main']['humidity']; ?><span class="temp-type">%</span></span>
                                          </span>
                                      </li>
                                      <li class="active">
                                          <span class="date">Wind Speed</span>
                                          <span class="lnr lnr-location condition">
                                              <span class="temp"><?php echo $weatherData['wind']['speed']; ?><span class="temp-type">m/s</span></span>
                                          </span>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                  </div>
                   
                <?php elseif ($type === 'forecast'): ?>
                    <h2><center>5-day Weather Forecast:</center></h2>
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
                                            <div class="top"  style="background: url('assets/img/<?php echo str_replace(' ', '-', ($forecast['weather'][0]['description'])); ?>.png') no-repeat;  background-size: cover; background-position: center center;">
                                                <div class="wrapper">
                                                    <div class="mynav">
                                                       
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
                                                        <a href="javascript:;"><span style="    line-height: 25px;">&nbsp;</span></a>
                                                        <li class="active">
                                                            <span class="date">Humidity</span>
                                                            <span class="lnr lnr-drop condition">
                                                                <span class="temp"><?php echo $forecast['main']['humidity']; ?><span class="temp-type">%</span></span>
                                                            </span>
                                                        </li>
                                                        <li class="active">
                                                            <span class="date">Wind Speed</span>
                                                            <span class="lnr lnr-location condition">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
