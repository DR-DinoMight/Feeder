<x-pulse::card :cols="$cols" :rows="$rows" :class="$class" wire:poll.5s="">
    <x-pulse::card-header name="Fetched Articles">

    </x-pulse::card-header>

    <x-pulse::scroll :expand="$expand">
        @foreach ($fetchedArticles as $feed)
            {{ $feeds->where('id', $feed->key)->first()->title }} -
            {{ $feed->count }}
        @endforeach
    </x-pulse::scroll>
</x-pulse::card>
