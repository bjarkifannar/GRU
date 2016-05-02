<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	$forumID = null;
	$canRemoveCategory = FALSE;
	$userID = null;
	
	/* Set the page name for the title */
	$pageName = "Forum";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If there is no forum id then go back to the index, else get the id */
			if (!isset($_GET['id'])) {
				header('Location: index.php');
			} else {
				$forumID = $_GET['id'];
			}

			/* Prepare to fetch the categories of this forum */
			$categoryQuery = "SELECT id, category_name, category_desc FROM categories WHERE forum_id=:forum_id";
			$categoryRes = $db->prepare($categoryQuery);
			$categoryRes->bindParam(':forum_id', $forumID);
			$categoryRes->execute();
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';

			/* If this user is logged in */
			if ($logged == "in") {
				/* Get the user ID */
				$userID = $_SESSION['user_id'];

				/* Check if this user can remove categories */
				$userRoleQuery = "SELECT role_id FROM users WHERE id=:user_id LIMIT 1";
				$userRoleRes = $db->prepare($userRoleQuery);
				$userRoleRes->bindParam(':user_id', $userID);
				$userRoleRes->execute();

				while ($row = $userRoleRes->fetch(PDO::FETCH_ASSOC)) {
					/* If the user is an admin */
					if ($row['role_id'] == 3) {
						/* This user can remove categories */
						$canRemoveCategory = TRUE;
					}
				}

				$userRoleRes = null;
			}
		?>
		<table class="forum-category-table">
			<thead>
				<tr>
					<th>
						<h2>Categories:</h2>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					/* Fetch the category information and show it */
					while ($row = $categoryRes->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr>
					<td>
						<a href="category.php?cid=<?php echo $row['id']; ?>"><h3><?php echo $row['category_name']; ?></h3></a>
						<p><?php echo $row['category_desc']; ?></p>
				<?php
						/* If the user can remove categories */
						if ($canRemoveCategory) {
				?>
				<a href="remove_category.php?cid=<?php echo $row['id']; ?>">Remove Category</a>
				<?php
						}
				?>
					</td>
				</tr>
				<?php
					}

					/* Set the category result variable to null to free memory */
					$categoryRes = null;
				?>
				<tr>
					<td>
				<?php
					/* If the user is logged in */
					if ($logged == "in") {
						/* If it's an admin */
						if ($_SESSION['role_id'] == 3) {
							/* Let them add a category */
							echo '<a href="add_category.php?fid='.$forumID.'">Add category</a>';
						}
					}
				?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>