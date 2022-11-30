<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class  WeatherService
{
    /**
     * @param RedisCache $redisCache
     */
    public function __construct(private readonly RedisCache $redisCache)
    {
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return string
     */
    private function buildUrl(float $lat, float $lon): string
    {
        return sprintf(
            "https://api.openweathermap.org/data/2.5/weather?lat=%s&lon=%s&units=metric&appid=%s",
            $lat,
            $lon,
            env('WEATHER_APP_ID')
        );
    }


    /**
     * @param float $lat
     * @param float $lon
     * @return array
     */
    public function getWeather(float $lat, float $lon): array
    {
        if (!$this->redisCache->get($this->getkey($lat, $lon))) {
            $this->apiCall($lat, $lon);
        }

        return $this->redisCache->get($this->getkey($lat, $lon));
    }

    /**
     * @param float $lat
     * @param float $lon
     */
    private function apiCall(float $lat, float $lon): void
    {
        $apiURL = $this->buildUrl($lat, $lon);

        $response = Http::get($apiURL);
        $value = $response->json();
        $this->redisCache->set($this->getkey($lat, $lon), $value['main']);
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return string
     */
    private function getkey(float $lat, float $lon): string
    {
        return date('dmY') . '.' . $lat . '.' . $lon;
    }

}

