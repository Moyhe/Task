<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\CategoryFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Geni',
            'email' => 'Geni@gmail.com',
            'role' => 'admin',
        ]);

        CategoryFactory::new()->count(3)->create();
    }
}
