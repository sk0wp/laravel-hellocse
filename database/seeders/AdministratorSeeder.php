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
        $token = Str::random(60);
        echo $token;

        Administrator::create([
            'api_token' => hash('sha256', $token),
        ]);
    }
}
