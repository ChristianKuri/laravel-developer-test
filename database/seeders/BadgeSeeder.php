<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            ['name' => 'Beginner', 'threshold' => 0],
            ['name' => 'Intermediate', 'threshold' => 4],
            ['name' => 'Advanced', 'threshold' => 8],
            ['name' => 'Master', 'threshold' => 10],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                ['threshold' => $badge['threshold']]
            );
        }
    }
}
