<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tweet;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        Tweet::factory(50)->create();

        User::factory()->create([
            'name' => 'rameez',
            'email' => 'rameez@gmail.com',
            'password' => Hash::make('123456')
        ]);

        User::factory(99)
            ->sequence(fn ($sequence) => ['name' => 'Person '.$sequence->index + 2])
            ->create();

        foreach (range(1, 20) as $user_id) {
            Tweet::factory()->create(['user_id' => $user_id]);
            foreach (range(1, 20) as $user_id2) {
                User::find($user_id)->follows()->attach(User::find($user_id2));
            }
        }

        foreach (range(21, 40) as $user_id) {
            Tweet::factory()->create(['user_id' => $user_id]);
            foreach (range(21, 40) as $user_id2) {
                User::find($user_id)->follows()->attach(User::find($user_id2));
            }
        }

        foreach (range(41, 60) as $user_id) {
            Tweet::factory()->create(['user_id' => $user_id]);
            foreach (range(41, 60) as $user_id2) {
                User::find($user_id)->follows()->attach(User::find($user_id2));
            }
        }

        foreach (range(61, 80) as $user_id) {
            Tweet::factory()->create(['user_id' => $user_id]);
            foreach (range(61, 80) as $user_id2) {
                User::find($user_id)->follows()->attach(User::find($user_id2));
            }
        }

        foreach (range(81, 100) as $user_id) {
            Tweet::factory()->create(['user_id' => $user_id]);
            foreach (range(81, 100) as $user_id2) {
                User::find($user_id)->follows()->attach(User::find($user_id2));
            }
        }
    }
}
