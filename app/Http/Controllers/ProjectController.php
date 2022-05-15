<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', ['projects' => Auth::user()->projects]);
    }

    public function show(Project $project)
    {
        abort_if($project->owner->id !== Auth::id(), 403);
        return view('projects.show', ['project' => $project]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);

        Auth::user()->projects()->create($attributes);

        return redirect('/projects');
    }
}
