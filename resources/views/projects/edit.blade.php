<x-app-layout>
    <x-slot name="header">
        New Project
    </x-slot>
    <form action="/projects" method="post">
        @csrf

        <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                   for="title">Title</label>
            <input class="border border-gray-400 p-2 w-full"
                   type="text" name="title" id="title" required
                   value="{{ old('title') }}"
            >
            @error('title')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                   for="description">Description</label>
            <textarea class="border border-gray-400 p-2 w-full"
                      name="description" id="description" required
            >{{ old('description') }}</textarea>
            @error('description')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <x-button>Submit</x-button>
        <a href="/projects">
            <x-button type="button" class="bg-white border-gray-400 text-gray-700">Cancel</x-button>
        </a>
    </form>
</x-app-layout>
