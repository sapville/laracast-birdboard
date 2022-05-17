<x-app-layout>
    <x-slot name="header">
        <p><a href="/projects">My Projects</a> / {{$project->title}}</p>
    </x-slot>
    <main class="flex flex-col-reverse md:flex-row">
        <div class="w-full md:w-3/4 md:mr-2">
            <div class="mb-6">
                <h2>Tasks</h2>
                @foreach($project->tasks as $task)
                    <x-card class="mx-0">
                        <p>{{$task->body}}</p>
                    </x-card>
                @endforeach
                <x-card class="mx-0" :expand="true">
                    <form method="post" action="{{$project->path() . '/tasks'}}">
                        @csrf
                        <input class="w-full p-3" name="body" placeholder="Add a new task"/>
                    </form>
                </x-card>
            </div>
            <div>
                <h2>General Notes</h2>
                <x-card :expand="true" class="p-0 mx-0">
                    <textarea class="w-full border-0 -mb-2" rows="8"
                    >Lorem Ipsum
                    </textarea>
                </x-card>
            </div>
        </div>

        <div class="w-full mb-6 md:w-1/4 md:ml-1">
            <h2 class="hidden md:block"><br></h2>
            <x-project-card class="mx-0" :project="$project"/>
        </div>
    </main>
</x-app-layout>
