<?php
$client = $_COOKIE['scavenger-user'];
echo '<div class="wrap">';
echo '<header>';
	echo '<a class="header_wrap" href="set-user.php">';
		echo '<img class="ghost" src="../../assets/img/ghost-loading.gif"/>';
		echo '<h1>Scavenger</h1>';
	echo '</a>';
	?>
	<nav class="nav-collapse">
	  <ul>
<!-- 	    <li><a href="set-user.php">Set User</a></li> -->
	    <li><a href="copy-followers.php">Copy Followers Manually</a></li>
	    <li><a href="whitelist.php">Whitelist</a></li>
	    <li><a href="unfollow.php">Unfollow</a></li>
	    <li><a href="unfollow-all.php">Unfollow All</a></li>
	    <?php if (($client == 'graysonerhard') || ($client == 'aspenhourglass')) { ?>
	   <li><a href="weekly-cron.php">Manual Weekly Cron</a></li>
	<!-- 	<li><a href="set-dm.php">Set Direct Message</a></li> -->
		<?php } ?>
	    
	  </ul>
	</nav>
</header>
<?php
define("VERSION", "2.1 BETA");
echo "<div id='version'>v. ".VERSION." - @$client</div>";
?>


<div style="clear:both"></div>
<script>
	var nav = responsiveNav(".nav-collapse");
</script>