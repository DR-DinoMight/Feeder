<div class="container p-6 mx-auto">
    @if (session()->has('error'))
        <div class="alert alert-danger dark:text-gray-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4">
        <details @if ($filterSelected) open @endif class="mb-4">
            <summary class="text-lg font-semibold text-blue-300" wire:click.prevent.stop="toggleFilterSelected">Filters
            </summary>
            <div class="flex">
                <div class="w-1/3">
                    <div class="flex items-center">
                        <button type="button" wire:click.stop="toggleShowAll"
                            class="{{ $showAll ? 'bg-blue-600' : 'bg-gray-200' }} relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out bg-gray-200 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                            role="switch" aria-checked="false" aria-labelledby="annual-billing-label">
                            <span aria-hidden="true"
                                class=" {{ $showAll ? 'translate-x-5' : 'translate-x-0' }} inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-0 bg-white rounded-full shadow pointer-events-none ring-0"></span>
                        </button>
                        <span class="ml-3 text-sm" id="annual-billing-label">
                            <span class="font-medium text-gray-600">Show All</span> </span>
                    </div>
                </div>
                <div class="flex flex-wrap w-2/3 gap-4">
                    @foreach ($feeds as $feed)
                        <div class="flex items-center">
                            <button type="button" wire:click.stop="toggleFeed({{ $feed->id }})"
                                class="{{ $this->hasToggledFeed($feed->id) ? 'bg-blue-600' : 'bg-gray-200' }} relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out bg-gray-200 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                                role="switch" aria-checked="false" aria-labelledby="annual-billing-label">
                                <span aria-hidden="true"
                                    class=" {{ $this->hasToggledFeed($feed->id) ? 'translate-x-5' : 'translate-x-0' }} inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-0 bg-white rounded-full shadow pointer-events-none ring-0"></span>
                            </button>
                            <span class="flex gap-3 ml-3 text-sm" id="annual-billing-label">
                                <img src="{{ $feed->icon }}" class="w-5 h-5 rounded-full" /><span
                                    class="font-medium text-gray-600">{{ $feed->title }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </details>
        <button wire:click="markAllAsRead"
            class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-white uppercase transition bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25">
            Mark all as read
        </button>
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            @forelse ($articles as $item)
                <div class="p-4 bg-white dark:bg-gray-800 dark:hover:bg-gray-850 hover:opacity-65">
                    <a href="{{ $item['link'] }}" class="text-blue-600 dark:text-blue-200" target="_blank"
                        wire:click='markAsRead({{ $item->id }})' wire:click="markAsRead({{ $item['id'] }})">
                        <h3 class="flex text-lg font-semibold"><img src="{{ $item->feed->icon }}"
                                class="w-5 h-5 mr-4 rounded-full" />{{ $item['title'] }}</h3>

                        <p class="text-gray-400">By: {{ $item['author'] }}</p>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-300">
                            {{ $item->published_at->diffForHumans() }}
                        </p>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ Str::limit(strip_tags($item['description']), 180) }}
                        </div>
                    </a>
                    <div class="mt-4">
                        @if (!$item['is_read'])
                            <button wire:click="markAsRead({{ $item['id'] }})"
                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25">Mark
                                as read</button>
                        @else
                            <button wire:click="markAsUnread({{ $item['id'] }})"
                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-red-600 border border-transparent rounded-md hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 disabled:opacity-25">Mark
                                as unread</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-4 bg-white rounded-lg shadow cursor-pointer dark:bg-gray-800">
                    <h3 class="flex text-lg font-semibold text-blue-500">No articles found</h3>
                    <p class="text-gray-400">Change the filter to show unread</p>
                </div>
            @endforelse
        </div>
        <div class="flex justify-center mt-6">
            <button wire:click="loadMore"
                class="px-4 py-2 text-xs font-semibold text-white uppercase transition bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25">
                Load more
            </button>
        </div>
        </divL>

    </div>
