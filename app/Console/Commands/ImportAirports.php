<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Airport;

class ImportAirports extends Command
{
    protected $signature = 'airports:import';
    protected $description = 'Import airports from JSON';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $response = Http::get('https://raw.githubusercontent.com/NemoTravel/nemo.travel.geodata/master/airports.json');
        $airportsData = $response->json();

        foreach ($airportsData as $code => $data) {
            $airport = [
                'code' => $code,
                'city_name_en' => $data['cityName']['en'] ?? null,
                'city_name_ru' => $data['cityName']['ru'] ?? null,
                'airport_name_en' => $data['airportName']['en'] ?? null,
                'airport_name_ru' => $data['airportName']['ru'] ?? null,
                'country' => $data['country'] ?? null,
                'latitude' => $data['lat'] ?? null,
                'longitude' => $data['lng'] ?? null,
                'timezone' => $data['timezone'] ?? null,
            ];

            Airport::updateOrCreate(
                ['code' => $code],
                $airport
            );
        }

        $this->info('Airports imported successfully');
    }
}
