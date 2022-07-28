<x-app-layout>
    <x-slot name="header">
        Edit Project: {{$project->title}}
    </x-slot>
    <form action="{{$project->path()}}" method="post">
        @csrf
        @method('PATCH')
        <x-project.form
            :project="$project"
        />
    </form>
</x-app-layout>
