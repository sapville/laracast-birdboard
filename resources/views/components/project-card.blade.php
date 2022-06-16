@props(['project'])
<x-card :sideline="'blue'" {{ $attributes }}>
    <div class="flex flex-col h-full">
        <div class="flex-1">
            <h3 class="text-lg font-bold mb-2 leading-tight">
                <a href="{{$project->path()}}">{{$project->title}}</a>
            </h3>
            <div>{{\Illuminate\Support\Str::limit($project->description, 150)}}</div>
        </div>
        <form
            class="mt-2 flex justify-end"
            method="POST" action="{{$project->path()}}">
            @method('DELETE')
            @csrf
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-gray-300 hover:stroke-gray-600"
                     fill="none" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
    </div>
</x-card>
