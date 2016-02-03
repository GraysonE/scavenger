<?php

namespace Scavenger;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $fillable = [
        'screen_name',
        'account_id',
        'social_media_account_id'
    ];
}
