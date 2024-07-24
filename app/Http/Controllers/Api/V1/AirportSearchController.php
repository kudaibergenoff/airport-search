<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Repositories\AirportRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(title="Airport Search API", version="1.0")
 */
class AirportSearchController extends Controller
{
    public function __construct(private AirportRepositoryInterface $airportRepository){}
    /**
     * @OA\Get (
     ** path="/api/search",
     *   tags={"Поиск аэропортов"},
     *   summary="Список аэропортов",
     *   @OA\Parameter(
     *       name="query",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *            type="string"
     *       )
     *    ),
     *   @OA\Response(
     *      response=200,
     *       description="Успешно"
     *   )
     *)
     *
     * @return JsonResponse
     */
    public function airportSearch(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->input('query');
        $cacheKey = 'airports_search_' . $query;

        $airports = cache()->remember($cacheKey, 60, function () use ($query) {
            return $this->airportRepository->search($query);
        });

        return response()->json($airports);
    }
}
