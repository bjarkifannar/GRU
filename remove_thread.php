<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	/* Thread ID */
	$threadID = null;
	
	/* Set the page name for the title */
	$pageName = "Remove Thread";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If there is no thread ID then redirect to the index */
			if (!isset($_GET['tid'])) {
				header('Location: index.php');
			} else {
				$threadID = $_GET['tid'];
			}
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';

			/* If the user is logged in */
			if ($logged == "in") {
				/* Can the user remove this thread? */
				$canRemove = FALSE;

				$userID = $_SESSION['user_id'];

				/* Check if the user is an admin or a moderator */
				$userQuery = "SELECT role_id FROM users WHERE id=:user_id LIMIT 1";
				$userRes = $db->prepare($userQuery);
				$userRes->bindParam(':user_id', $userID);
				$userRes->execute();

				while ($row = $userRes->fetch(PDO::FETCH_ASSOC)) {
					/* If the user is an admin or moderator, he can remove the thread */
					if ($row['role_id'] == 2 || $row['role_id'] == 3) {
						$canRemove = TRUE;
					}
				}

				$userRes = null;

				/* If the user is not a moderator or an admin, check if this is the user that posted
				 * this thread */
				if ($canRemove == FALSE) {
					/* Get the thread starter ID */
					$starterIDQuery = "SELECT starter FROM threads WHERE id=:thread_id LIMIT 1";
					$starterIDRes = $db->prepare($starterIDQuery);
					$starterIDRes->bindParam(':thread_id', $threadID);
					$starterIDRes->execute();

					while ($row = $starterIDRes->fetch(PDO::FETCH_ASSOC)) {
						/* If this is the user that started this thread, he can remove it */
						if ($row['starter'] == $userID) {
							$canRemove = TRUE;
						}
					}

					$starterIDRes = null;
				}

				/* If the user can remove the thread, delete it from the database */
				if ($canRemove == TRUE) {
					/* Make the queries */
					$deletePostsQuery = "DELETE FROM posts WHERE thread_id=:thread_id";
					$deleteThreadQuery = "DELETE FROM threads WHERE id=:thread_id";

					/* Delete the posts */
					$deletePostsRes = $db->prepare($deletePostsQuery);
					$deletePostsRes->bindParam(':thread_id', $threadID);
					$deletePostsRes->execute();
					$deletePostsRes = null;

					/* Delete the thread */
					$deleteThreadRes = $db->prepare($deleteThreadQuery);
					$deleteThreadRes->bindParam(':thread_id', $threadID);
					$deleteThreadRes->execute();
					$deleteThreadRes = null;

					/* Let the user know and give him/her a link back to the index */
		?>
		<h2 align="center">Thread removed successfully!</h2>
		<p align="center"><a href="index.php">Go home</a></p>
		<?php
				} else {
					/* Let the user know he/she does not have permission to remove this thread */
					echo '<h2 align="center">You do not have permission to remove this thread!</h2>';
				}
			} else {
				/* The user is logged out */
				echo '<h2 align="center">You do not have permission to remove this thread!</h2>';
			}
		?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>