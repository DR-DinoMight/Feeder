<x-app-layout>
    <x-status-messages />
    <div>
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <livewire:add-feed />
                <livewire:infinite-feed-grid />
            </div>
        </div>
    </div>
</x-app-layout>
