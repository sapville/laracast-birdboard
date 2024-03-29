<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function store(Project $project, Request $request)
    {
        Gate::authorize('owner-member', $project);

        $attributes = $this->validate($request, [
            'body' => 'required'
        ]);

        $project->addTask($attributes['body']);
        return redirect($project->path());
    }

    public function update(Task $task, Request $request)
    {
        Gate::authorize('owner-member', $task->project);

        $validator = Validator::make(
            $request->all(),
            ['body' => 'required'],
            ['body.required' => 'A task description cannot be blank']
        );
        $validator->validateWithBag($task->path());

        $task->update([
            'body' => $validator->validated()['body'],
            'completed' => $request->boolean('completed')
        ]);

        return redirect($task->project->path());
    }
}
