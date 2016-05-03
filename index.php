<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	$canRemoveForum = FALSE;
	$userID = null;
	
	/* Set the page name for the title */
	$pageName = "Home";
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

			/* If the user is logged in */
			if ($logged == "in") {
				/* Get the user ID */
				$userID = $_SESSION['user_id'];

				/* Check if this user can remove forums */
				$userRoleQuery = "SELECT role_id FROM users WHERE id=:user_id LIMIT 1";
				$userRoleRes = $db->prepare($userRoleQuery);
				$userRoleRes->bindParam(':user_id', $userID);
				$userRoleRes->execute();

				while ($row = $userRoleRes->fetch(PDO::FETCH_ASSOC)) {
					/* If this user is an admin */
					if ($row['role_id'] == 3) {
						/* This user can remove forums */
						$canRemoveForum = TRUE;
					}
				}

				$userRoleRes = null;
			}

			/* Prepare to fetch the forums */
			$forumQuery = "SELECT id, forum_name FROM forums ORDER BY forum_name ASC";
			$forumRes = $db->prepare($forumQuery);
			$forumRes->execute();
		?>
		<table class="forum-list-table">
			<thead>
				<tr>
					<th colspan="2">
						<h2>Forums:</h2>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="forum-setup">	
					<td class="left-side">
						<h1>Forum Name</h1>
					</td>
					<td class="right-side">
						<h1>Last Post</h1>
					</td>
				</tr>
				<?php
					/* Fetch the forums and show them */
					while ($row = $forumRes->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr class="forum-setup">	
					<td class="left-side">
						<h3><a href="forum.php?id=<?php echo $row['id']; ?>" class="forum-link"><?php echo $row['forum_name']; ?></a></h3>
						<?php
							/* If this user can remove forums */
							if ($canRemoveForum) {
								/* Give him/her an option to remove this forum */
						?>
						<a href="remove_forum.php?fid=<?php echo $row['id']; ?>" class="text-dec-Forum-Name">Remove Forum</a>
						<?php
							}
						?>
					</td>
					<td class="right-side">
						<h3><a href="forum.php?id=<?php echo $row['id']; ?>"><?php echo $row['forum_name']; ?></a></h3><div class="text-dec-Last-Post">Poster: <a href="*">TestPoster</a></div>
					</td>
				</tr>
				<?php
					}

					/* Set the forum result variable to null to free memory */
					$forumRes = null;
				?>
			</tbody>
		</table>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>