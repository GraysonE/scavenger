<?php
ini_set('max_execution_time', 30000);
require 'html.inc';
require 'header.php';
require 'config.php';
require 'functions.php';
	
echo "<h4 class='help'>This is where you can unfollow people who you are following, but they never returned the favor.</h4>";
	
if ((isset($_POST['unfollow'])) && (isset($_POST['unfollowerArray']))) {
	
	$unfollowerArray = unserialize($_POST['unfollowerArray']);
	
	if($debug) {print_r($unfollowerArray);}
    
    unfollow_by_id($connection, $unfollowerArray, $dataFileDirectory);
    
    
} else {
	
	
	$canUnfollow = follow_check($connection, $debug, $myScreenName, $cursorTarget);
	
		if($debug) {echo "canFollow: $canFollow<br>";}
		
		
		if ($canUnfollow) {
			
			loading_gif();
			
			$unfollowerArray = find_unfollowers($connection, $myScreenName, $count, $debug);
				if($debug){echo'Unfollower Array Finished: ';print_r($unfollowerArray);}
			$unfollowerNumber = count($unfollowerArray);	
			
			$unfollowerArray = serialize($unfollowerArray);
			
			if ($unfollowerNumber > 0) {
				
				?><script src="../../assets/js/functions.js" type="text/javascript"></script><?php
				
				echo "<h2 class='unfollower_amount'>You have $unfollowerNumber people not following you back.</h2>";
			
				echo '<form method="POST" class="copy_form" id="unfollow_form">';
// 					echo '<button type="submit" onClick="'; echo "false updateNumbers($unfollowerArray);"; echo '" name="unfollow">Unfollow</button>';
					echo '<button type="submit" name="unfollow">Unfollow</button>';
					echo '<input type="hidden" name="unfollowerArray" value="'.$unfollowerArray.'">';
				echo '</form>'; 
				
				
			} else {
				echo'<h2>Everyone is following you back!</h2>';
				
			} 
			
	} elseif ($canFollow == '161') {
		?><script src="../../assets/js/functions.js" type="text/javascript"></script><?php
		echo "<div class='errorMessage'>You've reached the following limit.</div>";
	} elseif ($canFollow == '88') {
		?><script src="../../assets/js/functions.js" type="text/javascript"></script><?php
		echo "<div class='errorMessage'>You've reached the rate limit. Wait 15 minutes to refresh your personal profile look up.</div>";
	}
}

echo "<div id='resultWrap'>";
echo "</div>";
echo '</div>'; // wrap div

?>