<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @param WeatherService $weatherService
     */
    public function __construct(private readonly WeatherService $weatherService)
    {
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHome(Request $request): mixed
    {
        return [$request->user(), $this->weatherService->getWeather($request->user()->lat, $request->user()->lon)];
    }
}

