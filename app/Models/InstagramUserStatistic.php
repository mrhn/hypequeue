<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InstagramUserStatistics
 *
 * @property Carbon $date
 * @property int $followers
 * @property int $followed_by
 * @property int $posts
 *
 * @package App\Models
 */
class InstagramUserStatistic extends Model
{
    protected $fillable = [
        'followers',
        'followed',
        'posts',
        'date',
    ];

    protected $dates = [
        'date',
    ];


}
