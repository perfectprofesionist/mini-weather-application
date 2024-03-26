<?php



// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// API key for OpenWeatherMap (replace with your own API key)
$apiKey = $_ENV['OPENWEATHERMAP_API_KEY'];

// Initialize variables
$city = '';
$type = 'current'; // Default to current weather
$error = '';
$weatherData = array();

require_once __DIR__ . '/functions/formatTemperature.php';
require_once __DIR__ . '/functions/getWeatherData.php';
require_once __DIR__ . '/functions/processRequest.php';





?>