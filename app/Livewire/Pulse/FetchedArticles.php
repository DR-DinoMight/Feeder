<?php

namespace App\Livewire\Pulse;

use App\Models\Feed;
use Laravel\Pulse\Livewire\Card;

class FetchedArticles extends Card
{
    public function render()
    {
        $feeds = Feed::all();

        return view('livewire.pulse.fetched-articles', [
            'fetchedArticles' => $this->aggregate('new_articles', ['count']),
            'feeds' => $feeds,
        ]);
    }
}
