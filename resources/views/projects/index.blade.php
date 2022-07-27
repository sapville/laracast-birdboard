<div x-data="new_project">
    <x-app-layout>
        <x-slot name="header">
            <h3>Projects</h3>
            <x-button type="button" x-bind="trigger">New Project</x-button>
        </x-slot>

        <main class="-mx-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($projects as $project)
                <x-project-card :project="$project"/>
            @empty
                <div>No projects yet</div>
            @endforelse
        </main>

        <x-project.modal-form/>

    </x-app-layout>
</div>
