<div class="p-6 bg-white shadow dark:bg-gray-800 sm:rounded-lg">
    @if (session()->has('message'))
        <div class="alert alert-success dark:text-gray-200">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="addFeed">
        <div class="mb-4 form-group">
            <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">RSS Feed URL</label>
            <input type="text" id="url" wire:model="url"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-gray-300">
            @error('url')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit"
            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25">Add
            Feed</button>
    </form>
</div>
