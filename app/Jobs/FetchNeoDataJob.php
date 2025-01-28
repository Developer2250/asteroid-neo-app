<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchNeoDataJob
{
    use Dispatchable, Queueable, SerializesModels;

    protected $startDate;
    protected $endDate;
    protected $data;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle()
    {
        $response = Http::get(config('nasa.base_url'), [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'api_key' => config('nasa.api_key'),
        ]);

        if ($response->failed()) {
            $this->data = null;
        } else {
            $this->data = $this->processData($response->json());
        }
    }

    /**
     * Get the processed NEO data.
     *
     * @return array|null Processed NEO data or null if fetching failed
     */
    public function getData()
    {
        return $this->data;
    }

    private function processData($data)
    {
        $fastestAsteroid = [
            'id' => null,
            'speed' => 0,
        ];
        $closestAsteroid = [
            'id' => null,
            'distance' => PHP_INT_MAX,
        ];

        // Accumulates the sizes of all asteroids
        $totalSize = 0;

        // Count  the total number of asteroids
        $totalAsteroids = 0;

        // Count the number of asteroids for each day
        $dailyCounts = [];

        // Process each day's data
        foreach ($data['near_earth_objects'] as $date => $asteroids) {
            $dailyCounts[$date] = count($asteroids);
            foreach ($asteroids as $asteroid) {
                $speed = $asteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'];
                $distance = $asteroid['close_approach_data'][0]['miss_distance']['kilometers'];
                $size = ($asteroid['estimated_diameter']['kilometers']['estimated_diameter_min'] +
                    $asteroid['estimated_diameter']['kilometers']['estimated_diameter_max']) / 2;

                $totalSize += $size;
                $totalAsteroids++;


                // Update fastest asteroid if this one is faster
                if ($speed > $fastestAsteroid['speed']) {
                    $fastestAsteroid = [
                        'id' => $asteroid['id'],
                        'speed' => $speed,
                    ];
                }

                // Update closest asteroid if this one is closer
                if ($distance < $closestAsteroid['distance']) {
                    $closestAsteroid = [
                        'id' => $asteroid['id'],
                        'distance' => $distance,
                    ];
                }
            }
        }

        // Calculate the average size of all asteroids
        $averageSize = $totalAsteroids > 0 ? $totalSize / $totalAsteroids : 0;

        return [
            'fastestAsteroid' => $fastestAsteroid,
            'closestAsteroid' => $closestAsteroid,
            'averageSize' => $averageSize,
            'dailyCounts' => $dailyCounts,
        ];
    }
}
