<?php

namespace Scavenger;

use Illuminate\Database\Eloquent\Model;

class TempTargetUser extends Model
{
    protected $fillable = [
        'account_id',
        'screen_name',
        'social_media_account_id'
    ];
}
