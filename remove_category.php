<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	/* Category ID */
	$categoryID = null;
	
	/* Set the page name for the title */
	$pageName = "Remove Category";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If there is no category ID then redirect to the index */
			if (!isset($_GET['cid'])) {
				header('Location: index.php');
			} else {
				$categoryID = $_GET['cid'];
			}
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';

			/* If the user is logged in */
			if ($logged == "in") {
				/* Can the user remove this category? */
				$canRemove = FALSE;

				$userID = $_SESSION['user_id'];

				/* Check if the user is an admin */
				$userQuery = "SELECT role_id FROM users WHERE id=:user_id LIMIT 1";
				$userRes = $db->prepare($userQuery);
				$userRes->bindParam(':user_id', $userID);
				$userRes->execute();

				while ($row = $userRes->fetch(PDO::FETCH_ASSOC)) {
					/* If the user is an admin, he can remove the category */
					if ($row['role_id'] == 3) {
						$canRemove = TRUE;
					}
				}

				$userRes = null;

				/* If the user can remove the category, delete it from the database */
				if ($canRemove == TRUE) {
					/* Make the queries */
					$deletePostsQuery = "DELETE FROM posts WHERE category_id=:category_id";
					$deleteThreadsQuery = "DELETE FROM threads WHERE category_id=:category_id";
					$deleteCategoryQuery = "DELETE FROM categories WHERE id=:category_id";

					/* Delete the posts */
					$deletePostsRes = $db->prepare($deletePostsQuery);
					$deletePostsRes->bindParam(':category_id', $categoryID);
					$deletePostsRes->execute();
					$deletePostsRes = null;

					/* Delete the threads */
					$deleteThreadsRes = $db->prepare($deleteThreadsQuery);
					$deleteThreadsRes->bindParam(':category_id', $categoryID);
					$deleteThreadsRes->execute();
					$deleteThreadsRes = null;

					/* Delete the category */
					$deleteCategoryRes = $db->prepare($deleteCategoryQuery);
					$deleteCategoryRes->bindParam(':category_id', $categoryID);
					$deleteCategoryRes->execute();
					$deleteCategoryRes = null;

					/* Let the user know and give him/her a link back to the index */
		?>
		<h2 align="center">Category removed successfully!</h2>
		<p align="center"><a href="index.php">Go home</a></p>
		<?php
				} else {
					/* Let the user know he/she does not have permission to remove this category */
					echo '<h2 align="center">You do not have permission to remove this category!</h2>';
				}
			} else {
				/* The user is logged out */
				echo '<h2 align="center">You do not have permission to remove this category!</h2>';
			}
		?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>