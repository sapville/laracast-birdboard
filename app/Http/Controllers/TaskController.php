<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Project $project, Request $request)
    {
        $attributes = $this->validate($request, [
            'body' => 'required'
        ]);
        $project->addTask($attributes['body']);
        return redirect($project->path());
    }
}
