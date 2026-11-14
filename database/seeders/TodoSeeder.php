<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        $total = 10000;
        $batchSize = 1000;
        $now = now();

        $this->command->info('Seeding 10,000 todos...');

        for ($i = 0; $i < $total; $i += $batchSize) {

            $todos = [];

            for ($j = 1; $j <= $batchSize; $j++) {

                $number = $i + $j;

                $todos[] = [
                    'title' => "Todo Task {$number}",
                    'description' => "This is the description for task {$number}",
                    'is_completed' => fake()->boolean(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('todos')->insert($todos);
        }

        // Remove cached list after inserting new todos
        // Cache::forget('todos.all');

        $this->command->info('Successfully seeded 10,000 todos!');
    }
}