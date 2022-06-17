<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteProjectMemberRequest;
use App\Models\Project;
use App\Models\User;

class ProjectMembersController extends Controller
{
    public function store(Project $project, InviteProjectMemberRequest $request)
    {
        $project->inviteMember(User::query()->where('email', $request->validated()['email'])->get()->first());

        return redirect($project->path());
    }
}
