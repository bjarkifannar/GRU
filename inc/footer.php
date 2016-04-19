</div> <!-- .content-wrap -->
<footer>
	<div class="top">
		<div class="top-left">
			<h2>Content</h2>
			<div><?php if (stripos($_SERVER['REQUEST_URI'], 'index.php')){echo '<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/StimmiKex" data-widget-id="720911492674121728">Tweets by @StimmiKex</a>';} ?></div>
		</div>
		<div class="top-right">
			<h2>More content</h2>
			<ul>
				<li>wow</li>
				<li>no way</li>
				<li><div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div></li>
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

<?php
	if (stripos($_SERVER['REQUEST_URI'], 'profile.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'login.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'register.php')){}
	else if (stripos($_SERVER['REQUEST_URI'], 'search.php')){}
	else {require_once "media-buttons.php";}

 ?>

<?php $db = null; ?>