<?php

use Illuminate\Database\Seeder;

class globalSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $social_media_account_values = [
	        
		    ['consumer_key' => '4nJh80KE5bVyuyY9WlT5rTYlN'], 
		    ['consumer_secret' => 'dYvw5tYAc2xnoKw3wdHc13oIdq6i7kEt5ndaxfoA4d0kUgNPmw'], 
		    ['access_token' => '1286744324-DwTdCgdd8VrwOvEqJY6jWZjkomtijdmR4vbEvQF'],
		    ['access_token_secret' => 'FYSZyvgQlT0V2JasCzlAoOPIaVRVpTKANEn4PaTbqd7uz']
		];
	    
	    foreach ($social_media_account_values as $social_media_account_value)
	    {
//		    $db =
	    }
		DB::table('social_media_accounts')->insert($social_media_account_value);
    }
}
