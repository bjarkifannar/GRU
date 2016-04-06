<header>
	<nav>
		<ul class="nav">
			<li>
				<ul class="nav-left">
					<li>
						<a href="index.php">Home</a>
					</li>
				</ul>
			</li>
			<li>
				<ul class="nav-right">
					<?php
						if ($logged == "in") {
					?>
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