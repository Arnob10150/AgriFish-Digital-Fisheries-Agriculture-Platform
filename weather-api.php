<?php
header('Content-Type: application/toon');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Cache-Control: no-cache, must-revalidate');

require_once 'models/toon.php';
require_once 'controllers/WeatherController.php';

try {
    $weatherController = new WeatherController();

    $weatherData = $weatherController->getDashboardWeather();

    echo toon_encode($weatherData);

} catch (Exception $e) {
    http_response_code(500);
    echo toon_encode([
        'status' => 'error',
        'message' => 'Weather service temporarily unavailable',
        'temperature' => 25,
        'weather_description' => 'Service unavailable',
        'wind_speed' => 10,
        'humidity' => 70,
        'alerts' => ['Weather service is currently down']
    ]);
}
?>
