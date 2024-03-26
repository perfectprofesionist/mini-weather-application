<?php



// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

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
$apiKey = $_ENV['OPENWEATHERMAP_API_KEY'];

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