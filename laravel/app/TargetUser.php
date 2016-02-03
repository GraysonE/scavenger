<?php

namespace Scavenger;

use Illuminate\Database\Eloquent\Model;

class TargetUser extends Model
{
    protected $fillable = [
        'account_id',
        'screen_name',
        'whitelist',
        'to_follow',
        'social_media_account_id'
    ];
}
