<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\HistorySubmitScore;
use Illuminate\Support\Facades\DB;

class LeaderboardService extends Service
{

    public function submitScore($payload)
    {
        try {
            HistorySubmitScore::create($payload);

            $highscore = HistorySubmitScore::where('user_id', $payload['user_id'])
                ->where('level', $payload['level'])
                ->max('score');
    
            return [
                'submittedScore' => $payload,
                'highscore' => $highscore,
            ];
        } catch (Exception) {
            return null;
        }
        
    }
    public function getLeaderboard($level = "all", $page = 1, $username = null)
    {
        $limit = 25;
       
        $highestScores = DB::table('history_submit_scores')
            ->select('user_id', 'level', DB::raw('MAX(score) as total_score'))
            ->when($level !== "all", function ($query) use ($level) {
                return $query->where('level', $level);
            })
            ->groupBy('user_id', 'level');

            // Ranking Scheme Using ROW_NUMBER(), alternative scheme using RANK()
        $rankedQuery = DB::table('history_submit_scores')
            ->joinSub($highestScores, 'highest_scores', function ($join) {
                $join->on('history_submit_scores.user_id', '=', 'highest_scores.user_id');
                $join->on('history_submit_scores.level', '=', 'highest_scores.level');
            })
            ->join('users', 'users.id', '=', 'history_submit_scores.user_id')
            ->selectRaw('
                ROW_NUMBER() OVER (ORDER BY SUM(highest_scores.total_score) DESC, MAX(highest_scores.level) DESC) AS ranking,
                users.id AS user_id,
                users.name AS username,
                MAX(highest_scores.level) AS last_level,
                SUM(highest_scores.total_score) AS total_score
            ')
            ->groupBy('users.id', 'users.name');

        $rankedCTE = DB::table(DB::raw("({$rankedQuery->toSql()}) AS ranked_cte"))
            ->mergeBindings($rankedQuery);
        if ($username) {
            $filteredQuery = $rankedCTE->where('username', $username);
            $page = 1;
            $rankedCTE = $filteredQuery;
        }
        return $rankedCTE->paginate($limit, ['*'], 'page', $page);
    }

    

}