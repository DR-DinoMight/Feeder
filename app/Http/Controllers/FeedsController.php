<?php

namespace App\Http\Controllers;

use App\Actions\FeedTools;
use App\Jobs\FetchFeed;
use App\Models\Feed;
use Illuminate\Http\Request;

class FeedsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeds = auth()->user()->feeds;
        return view('feeds.index', compact('feeds'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Feed $feed)
    {
        $articles = $feed->articles()->paginate(10);
        return view('feeds.show', compact('feed', 'articles'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Feed::where('id', $id)->where('user_id', auth()->user()->id)->delete();
        return redirect(route('feeds.index'))->with('success', 'Feed has been deleted');
    }

    /**
     * Trigger a fetch of the feed.
     * @param Request $request
     * @param Feed $feed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request, Feed $feed)
    {
        dispatch(new FetchFeed($feed));
        return redirect(route('feeds.index'))->with('success', 'Feed has been fetched');
    }
}
