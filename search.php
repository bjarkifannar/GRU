<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Search";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';
		?>
	<div id="search-bar">
		<form id="search" method="post" action="index.php">
				<input type="text" class="search-text" name="q" size="21" placeholder="Search Here" maxlength="120">
				<input type="submit" value="search" name="search" class="search-button">
				<div class="search-radio">
					<input type="radio" name="searched" value="thread">Threads
  					<input type="radio" name="searched" value="name">Names
  					<input type="radio" name="searched" value="post">Posts
				</div>
		</form>
		<?PHP

			$thread_status = 'unchecked';
			$name_status = 'unchecked';
			$post_status = 'unchecked';

			if (isset($_POST['search'])) {
				$selected_radio = $_POST['searched'];

				if ($selected_radio == 'thread') {
					$thread_status = 'checked';
					echo "1";
				}
				else if ($selected_radio == 'name') {
					$name_status = 'checked';
					echo "2";
				}
				else if ($selected_radio == 'post'){
					$post_status = 'checked';
					echo "3";
				}
			}
		?>
		<div class="search-clear"></div>
	</div>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>