<?php

namespace Scavenger;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'screen_name',
        'account_id',
        'user_followed',
        'followed_timestamp',
        'unfollowed_timestamp',
        'whitelisted',
        'last_active',
        'to_unfollow',
        'social_media_account_id'
    ];
}
