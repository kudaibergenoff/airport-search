<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use JsonMachine\Exception\InvalidArgumentException;
use \JsonMachine\Items;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class ImportAirports extends Command
{
    protected $signature = 'airports:import';
    protected $description = 'Импортирование список аэропортов из JSON';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws InvalidArgumentException
     */
    private function fetchAndParseJson($url): \Generator
    {
        $response = Http::get($url);
        $tempFile = 'airports_temp.json';
        Storage::put($tempFile, $response->body());

        // Используем JsonMachine для потокового чтения данных
        $jsonStream = Items::fromFile(storage_path('app/' . $tempFile), ['decoder' => new ExtJsonDecoder(true)]);

        foreach ($jsonStream as $code => $details) {
            yield $code => $details;
        }

        Storage::delete($tempFile);
    }

    private function importAirportsData($url): void
    {
        $batchSize = 1000;
        $batch = [];

        foreach ($this->fetchAndParseJson($url) as $code => $data) {
            $batch[] = [
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

            if (count($batch) >= $batchSize) {
                $this->processBatch($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $this->processBatch($batch);
        }
    }

    private function processBatch($batch): void
    {
        DB::table('airports')->upsert($batch, ['code']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(): void
    {
        $startTime = microtime(true);

        $this->importAirportsData('https://raw.githubusercontent.com/NemoTravel/nemo.travel.geodata/master/airports.json');

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->info('Список аэропортов успешно импортированы');
        $this->info('Время выполнения: ' . number_format($executionTime, 2) . ' секунды');
    }
}
