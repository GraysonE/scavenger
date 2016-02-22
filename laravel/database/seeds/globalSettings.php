<?php

use Illuminate\Database\Seeder;
use Scavenger\SocialMediaAccount;
use Scavenger\User;

class globalSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		$socialMediaAccount = new SocialMediaAccount();
		$socialMediaAccount->account_type = 'twitter';
		$socialMediaAccount->screen_name = 'GraysonErhard';
		$socialMediaAccount->account_password = 'blamjob69';
		$socialMediaAccount->consumer_key = '4nJh80KE5bVyuyY9WlT5rTYlN';
		$socialMediaAccount->consumer_secret = 'dYvw5tYAc2xnoKw3wdHc13oIdq6i7kEt5ndaxfoA4d0kUgNPmw';
		$socialMediaAccount->access_token = '1286744324-cE9hzxLNxEjCDxslk3i6yMeuKBIkhpwFyUaFNg5';
		$socialMediaAccount->access_token_secret = 'FUYJ4ISw2KGA5slIsn0byOae6mOHih9eMu8FfkZjkoSR0';
		$socialMediaAccount->auto_follow = '1';
		$socialMediaAccount->auto_unfollow = '1';
		$socialMediaAccount->auto_whitelist = '1';
		$socialMediaAccount->user_id = '1';
		$socialMediaAccount->save();
		
		$user = new User();
		$user->username = "scavvy";
		$user->password = bcrypt("password");
		$user->email = "web@graysonerhard.com";
		$user->role = "0";
		$user->first_name = "Grayson";
		$user->last_name = "Erhard";
		$user->save();
		
    }
}
