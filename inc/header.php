<header>
	<nav>
		<ul class="nav">
			<li class="nav-flex-left">
				<ul class="nav-left">
					<li>
						<a href="index.php">Gnome</a>
					</li>
					<li>
						<a href="search.php">Search</a>
					</li>
				</ul>
			</li>
			<li class="nav-flex-right">
				<ul class="nav-right">
					<?php
						if ($logged == "in") {
					?>
					<li class="user-name-header">
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
	<nav class="mobile">
  		<button class="menu-trigger">&#9776;</button>
  		<div>
		    <ul>
		    	<li>
					<a href="index.php">Gnome</a>
				</li>
				<li>
					<a href="search.php">Search</a>
				</li>
				<?php
					if ($logged == "in") {
				?>
				<li>
					<a href="logout.php">Log out</a>
				</li>
				<li>
					<a href="profile.php">Profile</a>
				</li>
				<li class="hamburger-end"><p></p></li>
				<?php
					} else {
				?>
				<li>
					<a href="login.php">Login</a>
				</li>
				<li>
					<a href="register.php">Register</a>
				</li>
				<li class="hamburger-end"><p></p></li>
				<?php
					}
				?>
		    </ul>
 		</div>
	</nav>
</header>
<div class="mobile-fixer"></div>
<div class="content-wrap">