<?php

use Illuminate\Database\Seeder;
use App\Models\Todo;
use App\Models\User;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // ambil user pertama

        Todo::create([
            'user_id' => $user->id,
            'title' => 'Belajar Laravel Blade',
            'description' => 'Selesaiin tampilan dan routing blade',
            'is_done' => false,
            'due_date' => now()->addDays(3),
        ]);
    }
}

