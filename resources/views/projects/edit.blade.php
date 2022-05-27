<x-app-layout>
    <x-slot name="header">
        Edit Project: {{$project->title}}
    </x-slot>
    <form action="/projects" method="post">
        @csrf

        <x-project.form
            :project="$project"
        />
    </form>
</x-app-layout>
