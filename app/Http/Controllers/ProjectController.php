<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
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
        Gate::authorize('owner-only', $project);
        return view('projects.show', ['project' => $project]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $attributes = $this->validate_project( $request);

        Auth::user()->projects()->create($attributes);

        return redirect('projects.index');
    }

    public function update(Project $project, Request $request)
    {
        Gate::authorize('owner-only', $project);

        $project->update($this->validate_project($request));
        return redirect($project->path());
    }

    private function validate_project(Request $request): array
    {
        return $request->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'nullable'
        ]);
    }
}
