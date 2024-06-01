<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchIconsForFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-icons-for-feeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $feeds = \App\Models\Feed::all();
        foreach ($feeds as $feed) {
            $this->info('Fetching icon for feed: ' . $feed->title);
            $feed->icon = \App\Actions\FeedTools::fetchFavicon($feed->url);
            $this->info('Icon fetched for feed: ' . $feed->title . ' - ' . $feed->icon);
            $feed->save();
        }
    }
}
