@props(['project'])
<x-card :sideline="'blue'" {{ $attributes }}>
    <div>
        <h3 class="text-lg font-bold mb-2 leading-tight">
            <a href="{{$project->path()}}">{{$project->title}}</a>
        </h3>
        <div>{{\Illuminate\Support\Str::limit($project->description, 150)}}</div>
    </div>
</x-card>
