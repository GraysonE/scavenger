<div property="mainContentOfPage">
  <?php the_content(); ?>

<?php 
ini_set('max_execution_time', 30000);
$stylesheetPath = get_stylesheet_directory_uri();
	//echo $stylesheetPath;
require "$stylesheetPath/assets/php/config.php";
	
	
$unfollowerArray = find_unfollowers($connection, $myScreenName, $count, $sleep, $debug, $numberOfFollowers, $cursorTarget);
	if($debug){echo'Unfollower Array Finished: ';print_r($unfollowerArray);}
$unfollowerNumber = count($unfollowerArray);	

if ($unfollowerNumber != 0) {
	?><h2>You have <?php echo $unfollowerNumber;?> people not following you back.</h2>

<form method="POST" action="<?php echo $stylesheetPath;?>/templates/content-unfollow.php">
		<button type="submit" name="unfollow">Unfollow</button>
	</form> 
	<?php
	if (isset($_REQUEST['unfollow'])) {
	    unfollow($unfollowerArray, $connection);
	}

/*
	$i=1;
	foreach($unfollowerArray as $unfollower) {
		
		echo "$i: $unfollower<br>";
		$i++;
	}
*/

} else {
	echo'<h2>Everyone is following you back!</h2>';
	
}

?>


</div>
<!-- /.main-body -->
<?php satus_paged_nav(); ?>
