<?php

namespace Tests;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function logIn(User $user = null): User
    {
        $user ??= User::factory()->create();
        Auth::login($user);
        return $user;
    }

    public static function createProject(array $attributes=[], User $user=null, bool $logon=true): Project
    {
        $project = $logon ? static::logIn($user)->projects() : new Project();
        return $project->create(Project::factory()->raw($attributes));
    }

    public static function updateTask($project=null, $old_task=null, $new_task=null): array
    {
        $project ??= static::createProject();
        $old_task ??= $project->addTask(Task::factory()->make(['project_id' => $project->id])->body);
        $new_task ??= Task::factory()->make([
            'project_id' => $project,
            'completed' => true
        ]);
        return array($project, $old_task, $new_task);
    }

    public static function updateProject(array $attributes=[], $project=null): Model
    {
        $project ??= static::createProject();
        $new_attributes = Project::factory()->raw($attributes);
        $project->setRawAttributes(array_merge(['id' => $project->id], $new_attributes));
        return $project;
    }

}
