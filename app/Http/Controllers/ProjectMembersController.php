<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectMembersController extends Controller
{
    public function store(Project $project, Request $request)
    {
        $user = User::whereKey($request->get('user_id'))->first();
        if ($user)
            $project->inviteMember($user);
    }
}
