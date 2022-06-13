<?php

namespace Tests\Unit\BirdBoard;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_linked_to_projects()
    {
        $user = User::factory()->create();
        Project::factory(3)->create(['owner_id' => $user->id]);

        $projects = $user->projects;

        $this->assertCount(3, $user->projects);
        $this->assertInstanceOf(Collection::class, $projects);
    }

    public function test_a_user_has_accessible_projects()
    {
        $project = self::createProject();
        $user = User::factory()->create();
        $project->inviteMember($user);

        $this->assertTrue($user->accessibleProjects()->contains($project));
    }
}
