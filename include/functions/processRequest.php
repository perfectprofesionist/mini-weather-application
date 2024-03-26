<?php

$apiKey = $_ENV['OPENWEATHERMAP_API_KEY'];

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