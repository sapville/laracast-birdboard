<x-app-layout>
    <x-slot name="header">
        {{$project->title}}
    </x-slot>
    <p>{{$project->description}}</p>
    <x-back-link/>
</x-app-layout>
