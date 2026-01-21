<?php


require_once '../models/Weather.php';
require_once '../models/toon.php';

class WeatherController {
    private $weatherModel;

    public function __construct() {
        $this->weatherModel = new Weather();
    }

   
    public function getDashboardWeather() {
       
        $weatherData = $this->weatherModel->getWeatherData(22.3569, 91.7832, 'Chittagong Coast');
        return $weatherData;
    }

   
    public function getLocationWeather($latitude, $longitude, $locationName = '') {
        return $this->weatherModel->getWeatherData($latitude, $longitude, $locationName);
    }

    
    public function apiGetWeather() {
        header('Content-Type: application/json');

        $lat = $_GET['lat'] ?? 22.3569;
        $lon = $_GET['lon'] ?? 91.7832;
        $location = $_GET['location'] ?? 'Chittagong Coast';

        // Validate coordinates
        if (!is_numeric($lat) || !is_numeric($lon) ||
            $lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            http_response_code(400);
            echo toon_encode(['error' => 'Invalid coordinates']);
            return;
        }

        $weatherData = $this->weatherModel->getWeatherData($lat, $lon, $location);
        echo toon_encode($weatherData);
    }

  
    public function getAllFishingLocationsWeather() {
        $locations = $this->weatherModel->getFishingLocations();
        $weatherData = [];

        foreach ($locations as $key => $location) {
            $weatherData[$key] = $this->weatherModel->getWeatherData(
                $location['lat'],
                $location['lon'],
                $location['name']
            );
           
            usleep(100000); 
        }

        return $weatherData;
    }

   
    public function checkFishingConditions($weatherData) {
        $conditions = [
            'safe' => true,
            'warnings' => [],
            'recommendations' => []
        ];

        
        if ($weatherData['wind_speed'] > 15) {
            $conditions['safe'] = false;
            $conditions['warnings'][] = 'High wind speeds may be dangerous';
            $conditions['recommendations'][] = 'Consider postponing fishing trip';
        }

        if ($weatherData['temperature'] < 15) {
            $conditions['warnings'][] = 'Cold weather conditions';
            $conditions['recommendations'][] = 'Wear appropriate warm clothing';
        }

        
        $severeCodes = [95, 96, 99, 65, 82, 86]; 
        if (isset($weatherData['current_weather_code']) &&
            in_array($weatherData['current_weather_code'], $severeCodes)) {
            $conditions['safe'] = false;
            $conditions['warnings'][] = 'Severe weather conditions';
            $conditions['recommendations'][] = 'Stay ashore and monitor weather updates';
        }

        
        if (!empty($weatherData['alerts'])) {
            $conditions['safe'] = false;
            $conditions['warnings'] = array_merge($conditions['warnings'], $weatherData['alerts']);
        }

        return $conditions;
    }

   
    public function formatWeatherForDisplay($weatherData) {
        return [
            'location' => $weatherData['location'],
            'temperature' => $weatherData['temperature'] . '°C',
            'wind_speed' => $weatherData['wind_speed'] . ' km/h',
            'description' => ucfirst($weatherData['weather_description']),
            'humidity' => $weatherData['humidity'] . '%',
            'alerts' => $weatherData['alerts'],
            'last_updated' => $weatherData['last_updated'],
            'status' => $weatherData['status']
        ];
    }

   
    private $cache = [];

    public function getCachedWeather($lat, $lon, $cacheTime = 1800) { 
        $cacheKey = $lat . ',' . $lon;

        if (isset($this->cache[$cacheKey]) &&
            (time() - $this->cache[$cacheKey]['timestamp']) < $cacheTime) {
            return $this->cache[$cacheKey]['data'];
        }

        $data = $this->weatherModel->getWeatherData($lat, $lon);
        $this->cache[$cacheKey] = [
            'data' => $data,
            'timestamp' => time()
        ];

        return $data;
    }
}
?>
