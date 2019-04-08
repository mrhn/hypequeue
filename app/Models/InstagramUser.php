<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InstagramUser
 *
 * @property int $id
 * @property string $handle
 * @property Collection $statistics
 *
 * @package App\Models
 */
class InstagramUser extends Model
{
    protected $fillable = [
        'handle',
    ];

    public function statistics()
    {
        return $this->hasMany(InstagramUserStatistic::class);
    }
}
