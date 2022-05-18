<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Project $project, Request $request)
    {
        if (Auth::id() !== $project->owner_id)
            abort(403);

        $attributes = $this->validate($request, [
            'body' => 'required'
        ]);

        $project->addTask($attributes['body']);
        return redirect($project->path());
    }

    public function update(Project $project, Task $task, Request $request)
    {
        if (Auth::id() !== $project->owner_id)
            abort(403);

        $attributes = $request->validate([
           'body' => 'required'
        ]);

        $task->update([
            'body' => $attributes['body'],
            'completed' => $request->has('completed')
        ]);

        return redirect($project->path());
    }
}
