<?php

namespace App\Domain\Services;

use Illuminate\Support\Collection;

interface AirportServiceInterface
{
    public function search(string $query): Collection;
}
