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
			<form id="search" method="post" action="search.php">
					<input type="text" class="search-text" name="q" size="21" placeholder="Search Here" maxlength="120">
					<input type="submit" value="search" name="search" class="search-button">
					<div class="search-radio">
						<input type="radio" name="searched" value="thread" checked>Threads
	  					<input type="radio" name="searched" value="name">Names
	  					<input type="radio" name="searched" value="post">Posts
					</div>
			</form>
			<div class="search-clear"></div>
			<?PHP

				$thread_status = 'unchecked';
				$name_status = 'unchecked';
				$post_status = 'unchecked';

				if (isset($_POST['search'])) {
					$selected_radio = $_POST['searched'];

						$q = $_POST['q'];

					if ($selected_radio == 'thread') {
						$thread_status = 'checked';
						$search_query = "SELECT thread_name FROM threads WHERE thread_name LIKE :q ORDER BY thread_name ASC";
						$queryRes = $db->prepare($search_query);
						$queryRes->execute(['q' => "%{$q}%"]);

						while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
							echo $row['thread_name']. '<br>';
						}

						$queryRes = null; 
					}
					else if ($selected_radio == 'name') {
						$name_status = 'checked';
						$search_query = "SELECT username FROM users WHERE username LIKE :q ORDER BY username ASC";
						$queryRes = $db->prepare($search_query);
						$queryRes->execute(['q' => "%{$q}%"]);

						while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
							echo $row['username']. '<br>';
						}
					}
					else if ($selected_radio == 'post'){
						$post_status = 'checked';
						$search_query = "SELECT post_name FROM users WHERE post_name LIKE :q ORDER BY post_name ASC";
						$queryRes = $db->prepare($search_query);
						$queryRes->execute(['q' => "%{$q}%"]);

						while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
							echo $row['post_name']. '<br>';
						}
					}
				}
			?>
		</div>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>