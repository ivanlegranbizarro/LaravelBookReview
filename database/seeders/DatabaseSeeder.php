<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\BookFactory;
use Database\Factories\ReviewFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        BookFactory::new()->count(100)->create();
        ReviewFactory::new()->count(200)->create();
    }
}
