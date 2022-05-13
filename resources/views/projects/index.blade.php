<x-layout>
    <ul>
        @foreach($projects as $project)
            <li>
                <p>{{$project->title}}</p>
            </li>
        @endforeach
    </ul>
</x-layout>
