<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Search";
	$uid = null;
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
			<form id="search" method="get" action="search.php">
					<input type="text" class="search-text" name="q" size="21" placeholder="Search Here" maxlength="120">
					<input type="submit" value="search" name="search" class="search-button"><br>
					<div class="search-radio">
						<input type="radio" name="searched" value="thread" id="thread" checked><label for="thread">Threads</label>
	  					<input type="radio" name="searched" value="name" id="name"><label for="name">Names</label>
	  					<input type="radio" name="searched" value="post" id="post"><label for="post">Posts</label></b>
					</div>
			</form>
			<div class="search-clear"></div>
		</div>
			<?PHP

				$thread_status = 'unchecked';
				$name_status = 'unchecked';
				$post_status = 'unchecked';

				if (isset($_GET['search'])) {
					$selected_radio = $_GET['searched'];

						$q = $_GET['q'];
						$name_link = 'user.php?uid=';
						$thread_link = 'thread.php?tid=';
						if ($logged == "in") {$user_id = $_SESSION['user_id'];}

					if ($selected_radio == 'thread') {
						$thread_status = 'checked';
						$search_query = "SELECT thread_name, id FROM threads WHERE thread_name LIKE :q ORDER BY thread_name ASC";
						$queryRes = $db->prepare($search_query);
						$queryRes->execute(['q' => "%{$q}%"]);
			?>
			<table class="search-table">
				<tbody>
				<?php
					while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
						echo "<tr><td class='search-output'><a href='".$thread_link.$row['id']."'>".$row['thread_name']."</a></td></tr>";
					}
				?>
				</tbody>
			</table>
			<?php
						$queryRes = null; 
					}
					else if ($selected_radio == 'name') {
						$name_status = 'checked';
						$search_query = "SELECT username, id, profile_img FROM users WHERE username LIKE :q ORDER BY username ASC";
						$queryRes = $db->prepare($search_query);
						$queryRes->execute(['q' => "%{$q}%"]);

				?>
				<table class="search-table">
					<tbody>
					<?php
						while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {

							$user_query = "SELECT role_id FROM users WHERE id = :user_id";
							$userbarRes = $db->prepare($user_query);
							$userbarRes->bindParam(':user_id', $row['id']);
							$userbarRes->execute();

							echo "<tr>";

							if (is_null($row['profile_img'])) {
								echo "<td class='user-image'><img src=img/default-user-image.png width='100' height='100'/></td>";
							}
							else {
								echo "<td class='user-image'><img src=img/".$row['profile_img']." width='100' height='100'/></td>";
							}

							echo "<td class='search-output'><div class=\"search-name-left\"><a href='".$name_link.$row['id']."'>".$row['username']."</a><br>";

							while ($row2 = $userbarRes->fetch(PDO::FETCH_ASSOC)) {
								if ($row2['role_id'] == 3) {
									echo "<img src='img/userbar-admin.png'>";
								}
								else if ($row2['role_id'] == 2) {
									echo "<img src='img/userbar-mod.png'>";
								}
								else if ($row2['role_id'] == 1){
									echo "<img src='img/userbar-user.png'>";
								}
							}

							echo "</div>";

							$userbarRes = null;

							$threadsQuery = "SELECT COUNT(id) AS num_threads FROM threads WHERE starter=:starter";
							$threadsRes = $db->prepare($threadsQuery);
							$threadsRes->bindParam(':starter', $row['id']);
							$threadsRes->execute();

							echo '<div class="search-name-right">';

							while ($row3 = $threadsRes->fetch(PDO::FETCH_ASSOC)) {
								echo '<p><b>Threads:</b> '.$row3['num_threads'].'</p>';
							}

							$threadsRes = null;

							$postsQuery = "SELECT COUNT(id) AS num_posts FROM posts WHERE posted_by=:posted_by";
							$postsRes = $db->prepare($postsQuery);
							$postsRes->bindParam(':posted_by', $row['id']);
							$postsRes->execute();

							while ($row4 = $postsRes->fetch(PDO::FETCH_ASSOC)) {
								echo '<p><b>Posts:</b> '.$row4['num_posts'].'</p>';
							}

							$postsRes = null;

							echo '</div>';

							echo "</td></tr>";
						}
					?>
					</tbody>
				</table>
				<?php
						$queryRes = null; 
					}
					else if ($selected_radio == 'post'){
						$post_status = 'checked';
						$search_query = "SELECT post_name FROM posts WHERE post_name LIKE :q ORDER BY post_name ASC";
						$queryRes = $db->prepare($search_query);
						$queryRes->execute(['q' => "%{$q}%"]);

				?>
				<table class="search-table">
					<tbody>
					<?php

						while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
							echo "<tr><td class='search-output'>" .$row['post_name']."</td></tr>";
						}
					?>
					</tbody>
				</table>
				<?php
						$queryRes = null; 
					}
				}
			?>

		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>