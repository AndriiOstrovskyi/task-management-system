<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        Team::factory()->count(10)->create()->each(function ($team) use ($users) {
            $randomUsers = $users->random(rand(3, 5))->pluck('id');
            $team->users()->attach($randomUsers);
        });
    }
}
