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

        $this->call([
           ActivityTextSeeder::class
        ]);

        $user = User::factory()->create([
            'name' => 'TestUser',
            'email' => 'tst@tst.com',
            'password' => Hash::make('pass'),
        ]);

        Project::factory()->has(Task::factory(3))->for($user, 'owner')->create();
        Project::factory(2)->for($user, 'owner')->create();
        Project::factory(7)->create();

    }
}
