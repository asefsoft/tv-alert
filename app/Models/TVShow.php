<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TVShow extends Model
{
    use HasFactory;

    protected $table='tv_shows';

    protected $casts = [
//        'status' => PostStatus::class,

        'start_date' => 'immutable_date',
        'end_date' => 'immutable_date',
        'next_ep_date' => 'immutable_date',
        'genres' => 'array',
        'pictures' => 'array',
        'episodes' => 'array',
    ];
}
