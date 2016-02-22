<?php

namespace Scavenger;

use Illuminate\Database\Eloquent\Model;

class SocialMediaAccount extends Model
{
    protected $fillable = [
		'user_id',
		'account_type',
		'screen_name',
    	'consumer_key', 
    	'consumer_secret', 
    	'access_token', 
    	'access_token_secret', 
    	'account_password', 
    	'account_id',
    	'auto_follow',
    	'auto_unfollow',
    	'auto_whitelist',
    ];
}
