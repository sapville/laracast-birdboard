<?php

namespace Tests\Unit\BirdBoard;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_users_linked_to_projects()
    {
        $user = User::factory()->create();
        Project::factory(3)->create(['owner_id' => $user->id]);

        $projects = $user->projects;

        $this->assertCount(3, $user->projects);
        $this->assertInstanceOf(Collection::class, $projects);
    }
}
