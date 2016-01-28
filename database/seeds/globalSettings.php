<?php

use Illuminate\Database\Seeder;
use Scavenger\SocialMediaAccount;

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

		$socialMediaAccount->consumer_key = '4nJh80KE5bVyuyY9WlT5rTYlN';
		$socialMediaAccount->consumer_secret = 'dYvw5tYAc2xnoKw3wdHc13oIdq6i7kEt5ndaxfoA4d0kUgNPmw';
		$socialMediaAccount->access_token = '1286744324-DwTdCgdd8VrwOvEqJY6jWZjkomtijdmR4vbEvQF';
		$socialMediaAccount->access_token_secret = 'FYSZyvgQlT0V2JasCzlAoOPIaVRVpTKANEn4PaTbqd7uz';
		$socialMediaAccount->save();
    }
}
