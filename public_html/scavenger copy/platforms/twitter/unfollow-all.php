<?php
ini_set('max_execution_time', 30000);
require 'html.inc';
require 'header.php';
require 'config.php';
require 'functions.php';
	
echo "<h4 class='help'>This is where you can unfollow everyone who isn't in your whitelist. Yes, this even includes mutually-followed users.</h4>";
	
if ((isset($_POST['unfollow'])) && (isset($_POST['followingArray']))) {
	
	$followingArray = unserialize($_POST['followingArray']);
	
	//print_r($followingArray);
    
    unfollow_by_id($connection, $followingArray, $dataFileDirectory);
    
    
} else {
	
	
	$canUnfollow = follow_check($connection, $debug, $myScreenName, $cursorTarget);
	
		if($debug) {echo "canFollow: $canFollow<br>";}
		
		
		if ($canUnfollow) {
			
			loading_gif();
			
			$followingArray = find_followers($connection, $myScreenName, $count, $debug, $dataFileDirectory);
				if($debug){echo'Follower Array Finished: ';print_r($followingArray);}
			
			$followingNumber = count($followingArray);
				//echo $followingNumber. "<br>";
				
			$whitelistArray = getWhitelist($dataFileDirectory, 'ids');
			$whitelistCount = count($whitelistArray);
				//echo $whitelistCount;
			$finalFollowingNumber = $followingNumber - $whitelistCount;
			
			$followingArray = serialize($followingArray);
			
			if ($finalFollowingNumber > 0) {
				
				?><script src="../../assets/js/functions.js" type="text/javascript"></script><?php
				
				$whitelisters = getWhitelist($dataFileDirectory, 'ids');
				$amountOfWhiteListers = count($whitelisters);
				
				echo "<h2 class='unfollower_amount'>You have $amountOfWhiteListers people in your whitelist. This will unfollow everyone else.</h2>";
/*
				echo "<h2 class='unfollower_amount'>You have $followingNumber people that you are following.</h2>";
				
				echo "<h2 class='unfollower_amount'>You are following at least $finalFollowingNumber people (minus your whitelisted users).</h2>";
			
*/
				echo '<form method="POST" class="copy_form" id="unfollow_form">';
					echo '<button type="submit" name="unfollow">Unfollow</button>';
					echo '<input type="hidden" name="followingArray" value="'.$followingArray.'">';
				echo '</form>'; 
				
				
			} else {
				echo'<h2>Everyone not in your whitelist has been unfollowed!</h2>';
				
			} 
			
	} elseif ($canFollow == '161') {
		?><script type="text/javascript">hide_loading_gif();</script><?php
		echo "You've reached the following limit.";
	} elseif ($canFollow == '88') {
		?><script type="text/javascript">hide_loading_gif();</script><?php
		echo "You've reached the rate limit. Wait 15 minutes to refresh your personal profile look up.";
	}
}
echo '</div>'; // wrap div

?>