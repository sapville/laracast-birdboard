<x-app-layout>
    <x-slot name="header">
        <h3>Projects</h3>
    </x-slot>
    <div class="my-3">
        <a href="/projects/create" class="text-blue-600">Create Project</a>
    </div>
    <ul>
        @forelse($projects as $project)
            <li class="py-1">
                <a href="{{$project->path()}}" class="text-blue-600 visited:text-purple-600">{{$project->title}}</a>
            </li>
        @empty
            <li>No projects yet</li>
        @endforelse
    </ul>
</x-app-layout>
