<x-layout>
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
            <input class="border border-gray-400 p-2 w-full"
                   type="text" name="description" id="description" required
                   value="{{ old('description') }}"
            >
            @error('description')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <x-button>Submit</x-button>

    </form>
</x-layout>
