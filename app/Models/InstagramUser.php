<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InstagramUser
 *
 * @property string $handle
 *
 * @package App\Models
 */
class InstagramUser extends Model
{
    protected $fillable = [
        'handle',
    ];
}
