<?php

namespace App\Models;

use App\Data\TVShowData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class TVShow extends Model
{
    use HasFactory, WithData;

    protected $table = 'tv_shows';
    protected $dataClass = TVShowData::class;
    protected $guarded = [];
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
