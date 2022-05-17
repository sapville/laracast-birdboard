<?php

namespace Tests;

use App\Models\Project;
use App\Models\User;
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

    public static function createProject(User $user=null, bool $logon=true): Project
    {
        $project = $logon ? static::logIn($user)->projects() : new Project();
        return $project->create(Project::factory()->raw());
    }
}
