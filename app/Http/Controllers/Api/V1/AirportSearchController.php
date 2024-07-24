<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AirportSearchController extends Controller
{
    public function airportSearch(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->input('query');
        $cacheKey = 'airports_search_' . $query;

        $airports = cache()->remember($cacheKey, 60, function () use ($query) {
            return DB::table('airports')
                ->where('city_name_en', 'ILIKE', "%$query%")
                ->orWhere('city_name_ru', 'ILIKE', "%$query%")
                ->orWhere('airport_name_ru', 'ILIKE', "%$query%")
                ->orWhere('airport_name_ru', 'ILIKE', "%$query%")
                ->orWhere('country', 'ILIKE', "%$query%")
                ->get();
        });

        return response()->json($airports);
    }
}
