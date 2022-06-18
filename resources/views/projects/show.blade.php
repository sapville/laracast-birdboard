@php
    use Illuminate\Support\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <p><a href="/projects">My Projects</a> / {{$project->title}}</p>
        <a href="{{$project->path() . '/edit'}}">
            <x-button>Edit Project</x-button>
        </a>
    </x-slot>
    <div class="flex flex-col-reverse md:flex-row">
        <div class="w-full md:w-3/4 md:mr-2">
            <div class="mb-6">
                <h2>Tasks</h2>
                @foreach($project->tasks as $task)
                    @error('body', $task->path())
                    <div class="text-sm text-red-600 -mb-2">
                        {{$errors->{$task->path()}->first('body')}}
                    </div>
                    @enderror
                    <x-card class="mx-0" :expand="true">
                        <form method="post" action="{{$task->path()}}" id="{{$task->path()}}">
                            @csrf
                            @method('patch')
                            <div class="flex items-center">
                                <input
                                    required
                                    class="w-full p-3 {{$task->completed ? 'text-gray-300' : ''}} @error('body', $task->path()) border border-red-600 @else border-none @enderror"
                                    name="body"
                                    type="text"
                                    value="{{ old('body') ?? $task->body }}"/>
                                <input
                                    class="m-3"
                                    type="checkbox"
                                    name="completed"
                                    {{$task->completed ? 'checked' : ''}}
                                    {{--                                    onchange="document.getElementById('{{$task->path()}}').submit()"/>--}}
                                    onchange="this.form.submit()"/>
                            </div>
                        </form>
                    </x-card>
                @endforeach
                <x-card class="mx-0" :expand="true">
                    <form method="post" action="{{$project->path() . '/tasks'}}">
                        @csrf
                        <input class="w-full p-3 border-0" name="body" type="text" placeholder="Add a new task"/>
                    </form>
                </x-card>
            </div>
            <div>
                <h2>General Notes</h2>
                <form method="post" action="{{$project->path()}}">
                    <x-card :expand="true" class="p-0 mx-0">
                        @csrf
                        @method('patch')
                        <textarea class="w-full border-0 -mb-2" rows="8" name="notes" placeholder="Anything else?"
                        >{{$project->notes}}</textarea>
                    </x-card>
                    <x-button>Save</x-button>
                </form>
            </div>
        </div>

        <div class="w-full mb-6 md:w-1/4 md:ml-1">
            <h2 class="hidden md:block"><br></h2>
            <div class="flex flex-wrap">
                <img src="{{get_avatar($project->owner->id)}}"
                     title="{{$project->owner->name}}"
                     alt="avatar"
                     height="40"
                     width="40"
                     class="mr-2 mt-2 border-2 border-blue-400">
                @foreach($project->members as $member)
                    <img src="{{get_avatar($member->id)}}"
                         title="{{$member->name}}"
                         alt="avatar"
                         height="40"
                         width="40"
                         class="mr-2 mt-2">
                @endforeach
            </div>
            <x-project-card class="mx-0" :project="$project"/>

            @can('owner-only', $project)
                <x-card class="mx-0">
                    <div class="flex flex-col">
                        <h2 class="mb-1">Invite a Member</h2>
                        <form method="post" action="{{$project->path() . '/members'}}">
                            @csrf
                            @error('email', 'invite')
                            <div class="text-sm text-red-600">
                                {{$errors->invite->first('email')}}
                            </div>
                            @enderror
                            <input
                                class="focus:border focus:border-0 w-full @error('email', 'invite') border border-red-600 @else border-gray-300 @enderror"
                                required
                                name="email"
                                value="{{ old('email') }}"
                                type="email"/>
                            <x-button class="mt-4">Invite</x-button>
                        </form>
                    </div>
                </x-card>
            @endcan

            <x-card class="text-sm mx-0 border-l-4 border-transparent hidden md:block">
                <li class="list-none">
                    @foreach($project->activity as $activity)
                        <ul class="mb-1 last:mb-0">
                            You {{$activity->description}} the {{$activity->showActivitySubject()}}
                            <span
                                class="text-gray-300">{{$activity->updated_at->diffForHumans(syntax: Carbon::DIFF_ABSOLUTE)}}</span>
                        </ul>
                    @endforeach
                </li>
            </x-card>
        </div>
    </div>
</x-app-layout>
