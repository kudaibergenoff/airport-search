<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Services\AirportServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Airport Search API", version="1.0")
 */
class AirportSearchController extends Controller
{
    public function __construct(private readonly AirportServiceInterface $airportService){}
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
    public function airportSearch(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $cacheKey = 'airports_search_' . $query;

        $airports = cache()->remember($cacheKey, 60, function () use ($query) {
            return $this->airportService->search($query);
        });

        return response()->json($airports);
    }
}
