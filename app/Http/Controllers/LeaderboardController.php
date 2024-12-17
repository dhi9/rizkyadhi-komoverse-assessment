<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\LeaderboardService;
use App\Http\Requests\SubmitScoreRequest;

use Client\UnisyncClient;

class LeaderboardController extends Controller
{
    protected $leaderboardService;
    protected $unisyncClient;

    public function __construct(LeaderboardService $leaderboardService, UnisyncClient $unisyncClient)
    {
        $this->leaderboardService = $leaderboardService;
        $this->unisyncClient = $unisyncClient;
    }

    public function submitScore(SubmitScoreRequest $request)
    {
        $payload = $request->validated();
        try {
            $result = $this->leaderboardService->submitScore($payload);
            return response()->json([
                'message' => 'Success',
                'data' => $result,
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed'
            ], 500);
        }
    }

    public function leaderboard(Request $request)
    {

        $level = $request->query('level') ? $request->query('level') : "all";
        $page = $request->query('page') ? $request->query('page') : 1;
        $username = $request->query('username') ? $request->query('username') : null;
        $cacheKey = "leaderboard:{$level}:{$page}:{$username}";
        try {
            $leaderboard = Cache::remember($cacheKey, 60, function () use ($level, $page, $username) {
                return $this->leaderboardService->getLeaderboard($level, $page, $username);
            });
            return response()->json([
                'message' => 'Success',
                'data' => [
                    'totalPage'   => $leaderboard->lastPage(),
                    'currentPage' => $leaderboard->currentPage(),
                    'totalData'   => $leaderboard->total(),
                    'data'        => $leaderboard->items(), 
                ],
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed'
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function externalSubmit(Request $request)
    {
        $response = $this->unisyncClient->submitAssessment();

        return response()->json($response);
    }
}
