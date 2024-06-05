<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithPagination;

class InfiniteFeedGrid extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Session]
    public $filterSelected = false;
    #[Session]
    public $showAll;
    public $feeds;
    #[Session]
    public $filterFeeds;

    public $articles;
    public $amount = 10;
    public $showShowMoreButton = true;

    public function loadMore() {
        $this->amount += 10;
        $this->showShowMoreButton = $this->articles->count() > $this->amount;
    }

    public function mount() {
        $this->feeds = auth()->user()->feeds;
        $this->filterFeeds = $this->feeds;
    }

    public function toggleFeed($feedId)
    {
        $feed = $this->feeds->find($feedId);

        //if $feed is already in $filterFeeds, remove it
        if ($this->filterFeeds->contains(fn ($value, $key) => $value->id === $feed->id)) {
            $this->filterFeeds = $this->filterFeeds->reject(function ($value, $key) use ($feed) {
                return $value->id === $feed->id;
            });
        } else {
            //otherwise add it
            $this->filterFeeds->push($feed);
        }
    }

    public function hasToggledFeed($feedId)
    {
        return $this->filterFeeds->contains(function ($value, $key) use ($feedId) {
            return $value->id === $feedId;
        });
    }

    public function markAsRead($articleId)
    {
        Article::forUser(auth()->user())->find($articleId)->update(['is_read' => true]);
    }

    public function markAsUnread($articleId)
    {
        Article::forUser(auth()->user())->find($articleId)->update(['is_read' => false]);
    }

    public function markAllAsRead()
    {
        Article::forUser(auth()->user())->update(['is_read' => true]);
    }
    public function markAllAsUnread()
    {
        Article::forUser(auth()->user())->update(['is_read' => false]);
    }

    public function toggleFilterSelected()
    {
        $this->filterSelected = !$this->filterSelected;
    }

    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
    }

    public function render()
    {
        $this->articles = Article::forUser(auth()->user())
        ->where( function ($query) {
            if (!$this->showAll) {
                $query->where('is_read', false);
            }
        })
        ->where(function ($query) {
            if ($this->filterFeeds) {
                $query->whereIn('feed_id', $this->filterFeeds->pluck('id'));
            }
        })

        ->orderBy('published_at', 'desc')
        ->with('feed')
        ->take($this->amount)
        ->get();

        return view('livewire.infinite-feed-grid');
    }
}
