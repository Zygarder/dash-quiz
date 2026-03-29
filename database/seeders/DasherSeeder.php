<?php

namespace Database\Seeders;

use App\Models\Dasher;
use Illuminate\Database\Seeder;

class DasherSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 25 users
        Dasher::factory()->count(25)->create();
    }
}