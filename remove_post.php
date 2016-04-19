<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	/* Post id */
	$postID = null;
	
	/* Set the page name for the title */
	$pageName = "Remove Post";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If the post ID is not set then redirect to the index */
			if (!isset($_GET['pid'])) {
				header('Location: index.php');
			} else {
				$postID = $_GET['pid'];
			}
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';

			/* If the user is logged in */
			if ($logged == "in") {
				/* Can the user remove this post? */
				$canRemove = FALSE;

				/* Get the user id */
				$userID = $_SESSION['user_id'];

				/* Check if the user is an admin or a moderator */
				$userRoleQuery = "SELECT role_id FROM users WHERE id=:user_id LIMIT 1";
				$userRoleRes = $db->prepare($userRoleQuery);
				$userRoleRes->bindParam(':user_id', $userID);
				$userRoleRes->execute();

				while ($row = $userRoleRes->fetch(PDO::FETCH_ASSOC)) {
					if ($row['role_id'] == 2 || $row['role_id'] == 3) {
						$canRemove = TRUE;
					}
				}

				$userRes = null;

				/* If this user is not an admin or a moderator */
				if ($canRemove == FALSE) {
					/* Get the starter ID */
					$starterIdQuery = "SELECT posted_by FROM posts WHERE id=:post_id LIMIT 1";
					$starterIdRes = $db->prepare($starterIdQuery);
					$starterIdRes->bindParam(':post_id', $postID);
					$starterIdRes->execute();

					while ($row = $starterIdRes->fetch(PDO::FETCH_ASSOC)) {
						/* If this is the user that posted this post */
						if ($row['posted_by'] == $userID) {
							$canRemove = TRUE;
						}
					}

					$starterIdRes = null;
				}

				/* If this user can remove this post */
				if ($canRemove) {
					$deletePostQuery = "DELETE FROM posts WHERE id=:post_id LIMIT 1";
					$deletePostRes = $db->prepare($deletePostQuery);
					$deletePostRes->bindParam(':post_id', $postID);
					$deletePostRes->execute();
					$deletePostRes = null;

					/* Let the user know the post has been deleted */
		?>
		<h2 align="center">Post removed successfully!</h2>
		<p align="center"><a href="index.php">Go home</a></p>
		<?php
				} else {
					/* Let the user know he/she does not have permission to remove this post */
					echo '<h2 align="center">You do not have permission to remove this post!</h2>';
				}
			} else {
				/* The user is logged out */
				echo '<h2 align="center">You do not have permission to remove this post!</h2>';
			}
		?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>