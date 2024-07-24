<?php

namespace App\Domain\Services;

use App\Domain\Repositories\AirportRepositoryInterface;
use Illuminate\Support\Collection;

class AirportService implements AirportServiceInterface
{
    public function __construct(private readonly AirportRepositoryInterface $airportRepository){}

    public function search(string $query): Collection
    {
        return $this->airportRepository->search($query);
    }
}
