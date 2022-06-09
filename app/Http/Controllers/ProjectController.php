<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', ['projects' => Auth::user()->projects]);
    }

    public function show(Project $project)
    {
        Gate::authorize('owner-member', $project);

        return view('projects.show', ['project' => $project]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(UpdateProjectRequest $request)
    {
        $project = Auth::user()->projects()->create($request->validated());
        return redirect($project->path());
    }

    public function update(Project $project, UpdateProjectRequest $request)
    {
        $project->update($request->validated());
        return redirect($project->path());
    }

    public function edit(Project $project)
    {
        return view('projects.edit', ['project' => $project]);
    }

    public function destroy(Project $project)
    {
        Gate::authorize('owner-only', $project);
        $project->delete();
        return redirect()->route('projects.index');
    }
}
