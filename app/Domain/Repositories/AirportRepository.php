<?php

namespace App\Domain\Repositories;

use App\Models\Airport;
use Illuminate\Support\Collection;

class AirportRepository implements AirportRepositoryInterface
{
    public function __construct(private Airport $airport) {}

    public function search(string $query): Collection
    {
        return Airport::query()
            ->where('city_name_en', 'ILIKE', "%$query%")
            ->orWhere('city_name_ru', 'ILIKE', "%$query%")
            ->orWhere('airport_name_ru', 'ILIKE', "%$query%")
            ->orWhere('airport_name_ru', 'ILIKE', "%$query%")
            ->orWhere('country', 'ILIKE', "%$query%")
            ->get();
    }
}
