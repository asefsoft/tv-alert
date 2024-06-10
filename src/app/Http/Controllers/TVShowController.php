<?php

namespace App\Http\Controllers;

use App\Models\TVShow;

class TVShowController extends Controller
{
    // full info of a tvshow
    public function fullInfo(TVShow $tvshow)
    {
        return view('tvshow.full-info', [
            'tvshowId' => $tvshow->id,
            'title' => $tvshow->name,
            'description' => $tvshow->getShowDescription(150),
        ]);
    }

    // timeline
    public function timeline()
    {
        return view('tvshow.timeline', [
            'title' => 'Your series timeline',
        ]);
    }

    // search results
    public function search()
    {
        $term = request()->get('term');
        return view('tvshow.search-full', ['term' => $term]);
    }
}
