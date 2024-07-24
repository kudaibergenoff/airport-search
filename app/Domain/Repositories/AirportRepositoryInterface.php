<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Collection;

interface AirportRepositoryInterface
{
    public function search(string $query): Collection;
}
