<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	/* Forum ID */
	$forumID = null;
	
	/* Set the page name for the title */
	$pageName = "Remove Forum";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If there is no forum ID then redirect to the index */
			if (!isset($_GET['fid'])) {
				header('Location: index.php');
			} else {
				/* Get the forum ID */
				$forumID = $_GET['fid'];
			}
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';

			/* If this user is logged in */
			if ($logged == "in") {
				/* Can the user remove this forum? */
				$canRemove = FALSE;

				/* Get the user ID */
				$userID = $_SESSION['user_id'];

				/* Check if the user is an admin */
				$userQuery = "SELECT role_id FROM users WHERE id=:user_id LIMIT 1";
				$userRes = $db->prepare($userQuery);
				$userRes->bindParam(':user_id', $userID);
				$userRes->execute();

				while ($row = $userRes->fetch(PDO::FETCH_ASSOC)) {
					/* If this user is an admin */
					if ($row['role_id'] == 3) {
						/* he/she can remove this forum */
						$canRemove = TRUE;
					}
				}

				$userRes = null;

				/* If this user can delete this forum, then remove it from the database */
				if ($canRemove) {
					/* Make the queries */
					$deletePostsQuery = "DELETE FROM posts WHERE forum_id=:forum_id";
					$deleteThreadsQuery = "DELETE FROM threads WHERE forum_id=:forum_id";
					$deleteCategoriesQuery = "DELETE FROM categories WHERE forum_id=:forum_id";
					$deleteForumQuery = "DELETE FROM forums WHERE id=:forum_id LIMIT 1";

					/* Delete the posts */
					$deletePostsRes = $db->prepare($deletePostsQuery);
					$deletePostsRes->bindParam(':forum_id', $forumID);
					$deletePostsRes->execute();
					$deletePostsRes = null;

					/* Delete the threads */
					$deleteThreadsRes = $db->prepare($deleteThreadsQuery);
					$deleteThreadsRes->bindParam(':forum_id', $forumID);
					$deleteThreadsRes->execute();
					$deleteThreadsRes = null;

					/* Delete the categories */
					$deleteCategoriesRes = $db->prepare($deleteCategoriesQuery);
					$deleteCategoriesRes->bindParam(':forum_id', $forumID);
					$deleteCategoriesRes->execute();
					$deleteCategoriesRes = null;

					/* Delete the forum */
					$deleteForumRes = $db->prepare($deleteForumQuery);
					$deleteForumRes->bindParam(':forum_id', $forumID);
					$deleteForumRes->execute();
					$deleteForumRes = null;

					/* Let the user know and give him/her a link back to the index */
		?>
		<h2 align="center">Forum removed successfully!</h2>
		<p align="center"><a href="index.php">Go home</a></p>
		<?php
				} else {
					/* Let the user know he/she does not have permission to remove this forum */
					echo '<h2 align="center">You do not have permission to remove this forum!</h2>';
				}
			} else {
				/* The user is logged out */
					echo '<h2 align="center">You do not have permission to remove this forum!</h2>';
			}
		?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>