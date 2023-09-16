<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TVShow;
use Illuminate\Http\Request;

class TVShowController extends Controller
{
    //

    public function fullInfo(TVShow $tvshow) {
        return view('tvshow.full-info', ['tvshowId' => $tvshow->id]);
    }
}
