<x-app-layout>
    <x-slot name="header">
            <h3>Projects</h3>
            <a href="/projects/create">
                <x-button>New Project</x-button>
            </a>
    </x-slot>

    <main class="-mx-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($projects as $project)
            <x-project-card :project="$project"/>
        @empty
            <div>No projects yet</div>
        @endforelse
    </main>
</x-app-layout>
