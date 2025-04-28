<?php

namespace Database\Seeders;

use App\Models\Profile;
use Database\Factories\ProfileFactory;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::factory('3')->create();
        Profile::factory('2')->inactive()->create();
    }
}
