<?php

namespace Database\Seeders;

use App\Models\Administrator;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administrator::create([
            'api_token' => hash('sha256', Str::random(60)),
        ]);
    }
}
