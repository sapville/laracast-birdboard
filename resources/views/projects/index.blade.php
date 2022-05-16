<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h3>Projects</h3>
            <a href="/projects/create">
            <x-button>New Project</x-button>
            </a>
        </div>
    </x-slot>

    <div class="-mx-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($projects as $project)
            <div class="m-2 bg-white border border-gray-200 shadow-md">
                <div class="p-3">
                    <h3 class="text-lg font-bold mb-2 leading-tight">
                        <a href="{{$project->path()}}">{{$project->title}}</a>
                    </h3>
                    <div>{{\Illuminate\Support\Str::limit($project->description, 100)}}</div>
                </div>
            </div>
        @empty
            <li>No projects yet</li>
        @endforelse
    </div>
</x-app-layout>
