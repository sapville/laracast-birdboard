<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'TestUser',
            'email' => 'tst@tst.com',
            'password' => 'pass'
        ]);

        Project::factory(3)->create(['owner_id' => $user->id]);
        Project::factory(7)->create();

    }
}
