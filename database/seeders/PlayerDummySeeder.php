<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlayerDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batchSize = 200;
        $users = [];
        $historySubmitScores = [];
        $totalUsers = 10000;

        $lastUserId = DB::table('users')->max('id');
        $lastUserId = $lastUserId ?? 0;

        $dummyPassword = bcrypt('password');

        echo "Starting seeding...\n";
        // Create 10,000 users in batches
        for ($i = $lastUserId + 1; $i <= $totalUsers; $i++) {
            $users[] = [
                'name' => 'Player_' . $i,
                'email' => 'player_' . $i . '@dummy.com',
                'password' =>  $dummyPassword,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $lastLevel = rand(1, 10);
            $levels = range(1, $lastLevel);
            foreach ($levels as $level) {
                $historySubmitScores[] = [
                    'user_id' => $i,
                    'level' => $level,
                    'score' => rand(10, 1000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if ($i % $batchSize === 0 || $i === $totalUsers) {
                DB::table('users')->insert($users);
                $users = [];

                if (!empty($historySubmitScores)) {
                    DB::table('history_submit_scores')->insert($historySubmitScores);
                    $historySubmitScores = [];
                }
                echo "Inserted {$i}/{$totalUsers}\n";
            }
        }

        echo "Seeding completed.\n";
    }
}
