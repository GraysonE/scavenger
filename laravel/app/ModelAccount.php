<?php

namespace Scavenger;

use Illuminate\Database\Eloquent\Model;

class ModelAccount extends Model
{
    protected $fillable = [
        'model_user_id',
        'screen_name',
        'api_cursor',
        'social_media_account_id'
    ];
}
