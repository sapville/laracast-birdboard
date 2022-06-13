<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $john = User::factory()->create([
            'name' => 'john',
            'email' => 'john@tst.com',
            'password' => Hash::make('pass'),
        ]);

        $jack = User::factory()->create([
            'name' => 'jack',
            'email' => 'jack@tst.com',
            'password' => Hash::make('pass'),
        ]);

        Project::factory()->has(Task::factory(3))->for($john, 'owner')->create()->members()->attach($jack);
        Project::factory(2)->for($jack, 'owner')->create();
        Project::factory(7)->create();

    }
}
