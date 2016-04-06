<header>
	<nav>
		<ul class="nav">
			<li class="nav-flex-left">
				<ul class="nav-left">
					<li>
						<a href="index.php">Gnome</a>
					</li>
				</ul>
			</li>
			<li class="nav-flex-right">
				<ul class="nav-right">
					<?php
						if ($logged == "in") {
					?>
					<li>
						<p>Welcome <?php echo $_SESSION['username']; ?>!</p>
					</li>
					<li>
						<a href="logout.php">Log out</a>
					</li>
					<li>
						<a href="profile.php">Profile</a>
					</li>
					<?php
						} else {
					?>
					<li>
						<a href="login.php">Login</a>
					</li>
					<li>
						<a href="register.php">Register</a>
					</li>
					<?php
						}
					?>
				</ul>
			</li>
		</ul>
	</nav>
</header>
<div class="content-wrap">