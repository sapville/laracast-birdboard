<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', ['projects' => Project::all()]);
    }

    public function store(Request $request)
    {
        Project::query()->create($request->input());
        return redirect('/projects');
    }
}
