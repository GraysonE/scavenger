<?php

namespace Scavenger;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'screen_name',
        'account_id',
        'unfollowed_timestamp',
        'whitelisted',
        'unfollowed',
        'to_unfollow',
        'social_media_account_id'
    ];
}
