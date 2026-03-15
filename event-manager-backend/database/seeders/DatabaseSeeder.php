<?php

namespace Database\Seeders;

use App\Models\User;
# use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    # use WithoutModelEvents;

    public function run(): void {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Teszt User',
                'password' => 'password123',
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'agent@example.com'],
            [
                'name' => 'Helpdesk Agent',
                'password' => 'password123',
                'role' => 'agent',
            ]
        );
    }
}
