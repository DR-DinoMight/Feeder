<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Feeds') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <table class="w-full">
                    <thead>
                        <tr
                            class="font-semibold tracking-wide text-left text-gray-900 uppercase bg-gray-100 border-b border-gray-600 text-md dark:border-gray-700 dark:text-gray-400 dark:bg-gray-700">
                            <th class="px-4 py-3">Feed</th>
                            <th class="px-4 py-3">Url</th>
                            <th class="px-4 py-3">Last Fetched</th>
                            <th class="px-4 py-3" aria-label="actions"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($feeds as $feed)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">
                                    <img src="{{ $feed->icon }}" class="w-5 h-5 rounded-full" />
                                    {{ $feed->title }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ $feed->url }}" target="_blank"
                                        class="text-blue-500 hover:text-blue-700">
                                        {{ $feed->url }}
                                        <span class="sr-only">Opens in a new window</span>
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                @empty($feed->last_fetched_at)
                                    Not Fetched
                                @else
                                    {{ $feed->last_fetched_at?->format('d/m/Y H:i:s') }}
                                    <span class="text-gray-500">
                                        ({{ $feed->last_fetched_at?->diffForHumans() }})
                                    </span>
                                @endempty

                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col items-center">
                                    <x-link :href="route('feeds.show', $feed)" :active="request()->routeIs('feeds.show', $feed)">Details</x-link>
                                    <x-link :href="route('feeds.fetch', $feed)" :active="request()->routeIs('feeds.fetch', $feed)">Fetch</x-link>

                                    <form action="{{ route('feeds.destroy', $feed) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this feed?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit">Delete</x-button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-3 text-gray-500" colspan="4">
                                No feeds found.
                                <a href="{{ route('feeds.create') }}" class="text-blue-500 hover:text-blue-700">
                                    Create a feed
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>
