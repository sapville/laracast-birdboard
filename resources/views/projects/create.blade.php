<x-app-layout>
    <x-slot name="header">
        New Project
    </x-slot>
    <form action="/projects" method="post">
        @csrf

        <x-project.form/>
    </form>
</x-app-layout>
