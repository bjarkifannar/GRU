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
						<h1>Last Thread</h1>
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
						<?php 
							$threadquery = "SELECT t1.id AS thread_id, t1.thread_name AS thread_name, users.id AS user_id, users.username AS username
												FROM threads t1
													INNER JOIN users
														ON t1.starter=users.id
												WHERE t1.post_time = (SELECT MAX(post_time) FROM threads t2 WHERE t2.forum_id = :forum_id)
													LIMIT 1";
							$threadres = $db->prepare($threadquery);
							$threadres->bindParam(':forum_id', $row['id']);
							$threadres->execute();

							while ($row2 = $threadres->fetch(PDO::FETCH_ASSOC)) {
						?>
						<h3><a href="thread.php?tid=<?php echo $row2['thread_id']; ?>"><?php echo $row2['thread_name']; ?></a></h3><div class="text-dec-Last-Post">Poster: <a href="user.php?uid=<?php echo $row2['user_id']; ?>"><?php echo $row2['username']; ?></a></div>
						<?php 
							}

							$threadres = null; 
						?>
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