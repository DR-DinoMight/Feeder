<?php

namespace App\Actions;

use App\Models\Feed;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\HTMLToMarkdown\HtmlConverter;
use Masterminds\HTML5;
use SimplePie\Item;
use SimplePie\SimplePie;

class FeedTools {
     // Function to check if the URL is a valid RSS feed
    public static function isValidFeed($url)
    {
        $response = Http::get($url);
        if ($response->successful()) {
            $contentType = $response->header('Content-Type');
            return strpos($contentType, 'application/rss+xml') !== false ||
                strpos($contentType, 'application/atom+xml') !== false ||
                strpos($contentType, 'text/xml') !== false ||
                strpos($contentType, 'application/xml') !== false;
        }
        return false;
    }


    public static function findValidFeedUrl($url)
    {

        // Check if the given URL is a valid RSS feed
        if (self::isValidFeed($url)) {
            return $url;
        }

        // Fetch the HTML content of the given URL
        $response = Http::get($url);
        if (!$response->successful()) {
            return null;
        }

        $html = $response->body();
        // Use DOMDocument to parse the HTML and find feed URLs
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('link');

        foreach ($links as $link) {
            $rel = $link->getAttribute('rel');
            $type = $link->getAttribute('type');
            $href = $link->getAttribute('href');

            if (($rel == 'alternate' && ($type == 'application/rss+xml' || $type == 'application/atom+xml')) || ($rel == 'alternate' && $type == 'application/rss+xml')) {
                // Check if the found URL is a valid feed

                if (self::isValidFeed($href)) {
                    return $href;
                }
            }
        }

        return null;
    }

    public static function fetchFavicon($url) {
        //take the url and get the root domain
        $url = parse_url($url, PHP_URL_HOST);
        $url = explode('.', $url);
        $url = $url[count($url) - 2] . '.' . $url[count($url) - 1];
        $url = "https://www.google.com/s2/favicons?domain=" . $url;
        return $url;
    }

    private static function fetchFeed($url) : SimplePie
    {
        $feed = new SimplePie();

        $response = Http::get($url);
        if (!$response->successful()) {
            throw new \Exception("Failed to fetch feed: {$url}");
        }

        $feed->set_raw_data($response->body());
        $feed->init();
        $feed->handle_content_type();

        if ($feed->error())
        {
            throw new \Exception($feed->error());
        }

        return $feed;
    }

    public static function getFeedTitle($url) : string
    {
        $feed = self::fetchFeed($url);
        return $feed->get_title();
    }

    public static function fetchFeedItems($url)
    {
        $feed = self::fetchFeed($url);
        $items = [];
        foreach ($feed->get_items() as $item) {
            // dd(self::fetchContent($item));
            $items[] = [
                'title' => $item->get_title(),
                'link' => $item->get_permalink(),
                'description' => $item->get_description(),
                'published_at' => $item->get_date(),
                'author' => $item->get_author()->name ?? $item->get_author()->email,
                'content' => self::fetchContent($item),
                'category' => $item->get_category()?->term ?? null,
            ];
        }
        return $items;
    }

    public static function fetchContent(Item $item)
    {
        $data = null;
        if ($item->get_content(content_only: true) )
        {
            $data = $item->get_content(content_only: true);
        }
        //if $item->data['child'] has key "https://purl.org/rss/1.0/modules/content/" get ['encoded'][0]['data]
        if (isset($item->data['child']['https://purl.org/rss/1.0/modules/content/'])) {
            $data = $item->data['child']['https://purl.org/rss/1.0/modules/content/']['encoded'][0]['data'];
            Log::info("Found content in RSS feed");
            $html5Parser = new HTML5();
            $dom = $html5Parser->loadHTML($data);
            $data = $dom->saveHTML();
        } else {
            // try and get the Article content from the HTML, by fetching $item->get_permalink()
            // and using DOMDocument to parse the HTML and find article content
            $response = Http::get($item->get_permalink());
            if (!$response->successful()) {
                Log::alert("Unable to fetch Articles");
                return "";
            }
            try {
                $html = $response->body();
                $html5Parser = new HTML5();
                $dom = $html5Parser->loadHTML($html);

                $article = $dom->getElementsByTagName('article');
                if ($article) {
                    if ( $article->count() > 0 && $article->item(0)->nodeValue) {
                        $data = $article->item(0)->nodeValue;
                    }
                }
                Log::info("Found article in HTML");
            } catch (\Exception $e) {
                return '';
            }
        }


        if ($data) {
            $convertor = new HtmlConverter();
            Log::info("Converting content");
            $data = $convertor->convert($data);
            //remove <html xmlns="http://www.w3.org/1999/xhtml"><body> from $data
            $data = str_replace('<html xmlns="http://www.w3.org/1999/xhtml"><body>', '', $data);
            return $data;
            // return $data;
        }
        Log::info("No content found");
        return '';

    }


    public static function processArticles($feeds = null)
    {
        if ($feeds === null) {
            $feeds = Feed::where('last_fetched_at', '<', now()->subHours(1))
            ->orWhereNull('last_fetched_at')->get();
        }

        foreach ($feeds as $feed) {
            $feedItems = FeedTools::fetchFeedItems($feed->url);

            foreach ($feedItems as $feedItem) {
                /** @var \App\Models\Feed $feedItem */
                $feed->articles()->updateOrCreate([
                    'link' => $feedItem['link'],
                    'feed_id' => $feed->id,
                ],
                [
                    'title' => $feedItem['title'],
                    'content' => $feedItem['content'],
                    'description' => $feedItem['description'],
                    'category' => $feedItem['category'],
                    'author' => $feedItem['author'],
                    'published_at' => $feedItem['published_at']
                ]
                );
            }
            $feed->update(['last_fetched_at' => now()]);
        }
    }
}

