<?php
/**
 * Weather Model for DFAP
 * Fetches weather data from Open-Meteo API
 */

class Weather {
    private $apiUrl = 'https://api.open-meteo.com/v1/forecast';

    /**
     * Get weather data for a specific location
     */
    public function getWeatherData($latitude, $longitude, $locationName = '') {
        // Default to Chittagong, Bangladesh for fisherman dashboard
        $lat = $latitude ?: 22.3569;
        $lon = $longitude ?: 91.7832;
        $location = $locationName ?: 'Chittagong Coast';

        $params = [
            'latitude' => $lat,
            'longitude' => $lon,
            'current' => 'temperature_2m,wind_speed_10m,weather_code',
            'hourly' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
            'forecast_days' => 1,
            'timezone' => 'Asia/Dhaka'
        ];

        $url = $this->apiUrl . '?' . http_build_query($params);

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10, // 10 second timeout
                    'user_agent' => 'DFAP-Weather/1.0'
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response === false) {
                return $this->getFallbackData($location);
            }

            $data = json_decode($response, true);

            if (!$data || !isset($data['current'])) {
                return $this->getFallbackData($location);
            }

            return $this->formatWeatherData($data, $location);

        } catch (Exception $e) {
            return $this->getFallbackData($location);
        }
    }

    /**
     * Format API response into usable data
     */
    private function formatWeatherData($data, $location) {
        $current = $data['current'];
        $hourly = $data['hourly'];

        // Get weather description from WMO code
        $weatherDesc = $this->getWeatherDescription($current['weather_code']);

        // Check for alerts (high winds, etc.)
        $alerts = $this->checkForAlerts($hourly);

        return [
            'location' => $location,
            'temperature' => round($current['temperature_2m']),
            'wind_speed' => round($current['wind_speed_10m']),
            'weather_description' => $weatherDesc,
            'humidity' => isset($hourly['relative_humidity_2m'][0]) ? $hourly['relative_humidity_2m'][0] : 75,
            'alerts' => $alerts,
            'hourly_forecast' => $this->getHourlyForecast($hourly),
            'last_updated' => date('H:i'),
            'status' => 'success'
        ];
    }

    /**
     * Get weather description from WMO weather code
     */
    private function getWeatherDescription($code) {
        $descriptions = [
            0 => 'Clear sky',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45 => 'Fog',
            48 => 'Depositing rime fog',
            51 => 'Light drizzle',
            53 => 'Moderate drizzle',
            55 => 'Dense drizzle',
            61 => 'Slight rain',
            63 => 'Moderate rain',
            65 => 'Heavy rain',
            71 => 'Slight snow',
            73 => 'Moderate snow',
            75 => 'Heavy snow',
            77 => 'Snow grains',
            80 => 'Slight rain showers',
            81 => 'Moderate rain showers',
            82 => 'Violent rain showers',
            85 => 'Slight snow showers',
            86 => 'Heavy snow showers',
            95 => 'Thunderstorm',
            96 => 'Thunderstorm with slight hail',
            99 => 'Thunderstorm with heavy hail'
        ];

        return $descriptions[$code] ?? 'Unknown';
    }

    /**
     * Check for weather alerts
     */
    private function checkForAlerts($hourly) {
        $alerts = [];

        if (isset($hourly['wind_speed_10m'])) {
            $maxWind = max($hourly['wind_speed_10m']);
            if ($maxWind > 15) {
                $alerts[] = 'Strong winds expected (up to ' . round($maxWind) . ' km/h)';
            }
        }

        if (isset($hourly['weather_code'])) {
            $severeCodes = [95, 96, 99, 65, 82, 86]; // Thunderstorm, heavy rain, heavy snow
            foreach ($hourly['weather_code'] as $code) {
                if (in_array($code, $severeCodes)) {
                    $alerts[] = 'Severe weather conditions expected';
                    break;
                }
            }
        }

        return $alerts;
    }

    /**
     * Get simplified hourly forecast
     */
    private function getHourlyForecast($hourly) {
        $forecast = [];

        if (isset($hourly['time']) && isset($hourly['temperature_2m'])) {
            for ($i = 0; $i < min(6, count($hourly['time'])); $i++) {
                $time = date('H:i', strtotime($hourly['time'][$i]));
                $temp = round($hourly['temperature_2m'][$i]);
                $forecast[] = [
                    'time' => $time,
                    'temperature' => $temp
                ];
            }
        }

        return $forecast;
    }

    /**
     * Fallback data when API is unavailable
     */
    private function getFallbackData($location) {
        return [
            'location' => $location,
            'temperature' => 28,
            'wind_speed' => 12,
            'weather_description' => 'Partly cloudy',
            'humidity' => 78,
            'alerts' => ['Weather data temporarily unavailable'],
            'hourly_forecast' => [
                ['time' => '06:00', 'temperature' => 26],
                ['time' => '09:00', 'temperature' => 29],
                ['time' => '12:00', 'temperature' => 31],
                ['time' => '15:00', 'temperature' => 30],
                ['time' => '18:00', 'temperature' => 28],
                ['time' => '21:00', 'temperature' => 27]
            ],
            'last_updated' => date('H:i'),
            'status' => 'fallback'
        ];
    }

    /**
     * Get weather for major Bangladeshi fishing locations
     */
    public function getFishingLocations() {
        return [
            'chittagong' => ['name' => 'Chittagong Coast', 'lat' => 22.3569, 'lon' => 91.7832],
            'cox_bazar' => ['name' => 'Cox\'s Bazar', 'lat' => 21.4272, 'lon' => 92.0058],
            'kuakata' => ['name' => 'Kuakata', 'lat' => 21.8182, 'lon' => 90.1197],
            'patenga' => ['name' => 'Patenga', 'lat' => 22.2333, 'lon' => 91.7833],
            'sandwip' => ['name' => 'Sandwip', 'lat' => 22.5125, 'lon' => 91.4542]
        ];
    }
}
?>