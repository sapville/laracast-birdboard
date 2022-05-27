@props(['project' => null])
<div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
           for="title">Title</label>
    @error('title')
    <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
    <input class="border border-gray-400 p-2 w-full"
           type="text" name="title" id="title"
           required
           value="{{ old('title') ?? ($project->title ?? '') }}"
    >
</div>

<div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
           for="description">Description</label>
    @error('description')
    <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
    <textarea class="border border-gray-400 p-2 w-full"
              name="description" id="description"
              required
    >{{ old('description') ?? ($project->description ?? '') }}</textarea>
</div>

<x-button>Submit</x-button>
<a href="{{$project ? $project->path() : '/projects'}}">
    <x-button type="button" class="bg-white border-gray-400 text-gray-700">Cancel</x-button>
</a>
