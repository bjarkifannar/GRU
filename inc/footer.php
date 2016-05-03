</div> <!-- .content-wrap -->
<footer>
	<div class="top">
		<div class="top-left">
			<h2>Content</h2>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="search.php">Search</a></li>
				<li><a href="about.php">About</a></li>
			</ul>
		</div>
		<div class="top-right">
			<h2>More content</h2>
			<ul>
				<?php
						if ($logged == "in") {
					?>
					<li><a href="logout.php">Log out</a></li>
					<li><a href="profile.php">Profile</a></li>
					<li><p>Welcome <?php echo $_SESSION['username']; ?>!</p></li>
					<?php
						} else {
					?>
					<li><a href="login.php">Login</a></li>
					<li><a href="register.php">Register</a></li>
					<?php
						}
					?>
				<!--<li><div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div></li>-->
			</ul>
		</div>
	</div>
	<div class="bottom">
		<p>&copy; 2016 Lokaverkefni GRU; Nemendur: Bjarki Fannar Snorrason, Guðlaugur Kjartan Þorgeirsson og Styrmir Óli Þorsteinsson</p>
	</div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript" src="js/fb_api.js"></script>
<script type="text/javascript" src="js/media.js" ></script>
<script type="text/javascript" src="js/hamburger.js"></script>

<?php
	if (stripos($_SERVER['REQUEST_URI'], 'profile.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'login.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'register.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'search.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'user.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'upload_profile_img.php')){}
	else {require_once "media-buttons.php";}

 ?>

<?php $db = null; ?>