@props([
    'sidelines' => [
        'blue' => 'border-l-4 border-blue-400',
        'red' => 'border-l-4 border-red-400',
        'none' => '',
    ],
    'sideline' => 'none',
    'expand' => false,
])
<div {{ $attributes->class(['flex', 'm-2', 'bg-white', 'border', 'border-gray-200', 'shadow-md']) }}>
    <div class="{{$sidelines[$sideline]}}"></div>
    <div class="{{$expand ?: 'p-3'}} flex-1">
        {{$slot}}
    </div>
</div>
