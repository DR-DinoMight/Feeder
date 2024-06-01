<?php

namespace App\Livewire;

use App\Actions\FeedTools;
use App\Jobs\FetchArticles;
use Livewire\Component;

class AddFeed extends Component
{
    public ?string $url;

    protected function rules() {
        return [
            'url' => ['required', 'url', 'unique:feeds,url,NULL,id,user_id,' . auth()->id()],
        ];
    }

    public function addFeed() {
        $this->validate();

        // TODO: if the feed url is a website, view source code  and get the RSS feed URL from the source code.
        if (!FeedTools::isValidFeed($this->url))
        {
            $this->url = FeedTools::findValidFeedUrl($this->url);
        }
        $title = FeedTools::getFeedTitle($this->url);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->feeds()->create([
            'url' => $this->url,
            'title' => $title,
            'icon' => FeedTools::fetchFavicon($this->url),
        ]);

        session()->flash('message', 'Feed URL added successfully.');

        $this->reset('url');

        FetchArticles::dispatch();
    }


    public function render()
    {
        return view('livewire.add-feed');
    }
}
