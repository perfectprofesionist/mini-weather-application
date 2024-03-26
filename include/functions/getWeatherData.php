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