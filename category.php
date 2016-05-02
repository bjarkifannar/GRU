<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Category";

	/* Variables */
	$categoryID = null;
	$categoryName = null;
	$showOnlySolved = FALSE;
	$showOnlyUnsolved = FALSE;
	$solvedValue = null;

	/* If the category ID is not set then redirect to the index */
	if (!isset($_GET['cid'])) {
		header('Location: index.php');
	} else {
		$categoryID = $_GET['cid'];
	}

	/* If the solved variable is set */
	if (isset($_GET['solved'])) {
		/* If the solved variable is = 1 */
		if ($_GET['solved'] == 1) {
			/* Show only solved threads */
			$showOnlySolved = TRUE;
			$solvedValue = 1;
		} else if ($_GET['solved'] == 0) {
			/* Show only unsolved threads */
			$showOnlyUnsolved = TRUE;
			$solvedValue = 0;
		}
	}
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

			/* Get the category name */
			$categoryQuery = "SELECT category_name FROM categories WHERE id=:cat_id LIMIT 1";
			$categoryRes = $db->prepare($categoryQuery);
			$categoryRes->bindParam(':cat_id', $categoryID);
			$categoryRes->execute();

			while ($row = $categoryRes->fetch(PDO::FETCH_ASSOC)) {
				$categoryName = $row['category_name'];
			}

			$categoryRes = null;
		?>
		<table class="thread-list-table">
			<thead>
				<tr>
					<th>
						<h2><?php echo $categoryName; ?></h2>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<a href="category.php?cid=<?php echo $categoryID; ?>&solved=1">Show only solved</a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="category.php?cid=<?php echo $categoryID; ?>&solved=0">Show only unsolved</a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="category.php?cid=<?php echo $categoryID; ?>">Show all</a>
					</td>
				</tr>
		<?php
			/* If show only solved is true */
			if ($showOnlySolved) {
				/* Get all the threads in this category that are solved */
				$threadQuery = "SELECT threads.id AS thread_id,
										threads.thread_name AS thread_name,
										threads.starter AS starter_id,
										users.username AS starter_name
											FROM threads
												INNER JOIN users
													ON threads.starter=users.id
												WHERE threads.category_id=:cat_id
													AND solved=:solved_value";
				$threadRes = $db->prepare($threadQuery);
				$threadRes->bindParam(':cat_id', $categoryID);
				$threadRes->bindParam(':solved_value', $solvedValue);
				$threadRes->execute();
			} else if ($showOnlyUnsolved) {
				/* If show only unsolved is true */
				/* Get all the threads in this category that are unsolved */
				$threadQuery = "SELECT threads.id AS thread_id,
										threads.thread_name AS thread_name,
										threads.starter AS starter_id,
										users.username AS starter_name
											FROM threads
												INNER JOIN users
													ON threads.starter=users.id
												WHERE threads.category_id=:cat_id
													AND solved=:solved_value";
				$threadRes = $db->prepare($threadQuery);
				$threadRes->bindParam(':cat_id', $categoryID);
				$threadRes->bindParam(':solved_value', $solvedValue);
				$threadRes->execute();
			} else {
				/* Get all the threads in this category */
				$threadQuery = "SELECT threads.id AS thread_id,
										threads.thread_name AS thread_name,
										threads.starter AS starter_id,
										users.username AS starter_name
											FROM threads
												INNER JOIN users
													ON threads.starter=users.id
											WHERE threads.category_id=:cat_id";
				$threadRes = $db->prepare($threadQuery);
				$threadRes->bindParam(':cat_id', $categoryID);
				$threadRes->execute();
			}

			while ($row = $threadRes->fetch(PDO::FETCH_ASSOC)) {
		?>
			<tr class="thread-def">
				<td>
					<a href="thread.php?tid=<?php echo $row['thread_id']; ?>"><h3><?php echo $row['thread_name']; ?></h3></a>
					<a href="user.php?uid=<?php echo $row['starter_id']; ?>"><p><b><?php echo $row['starter_name']; ?></b></p></a>
					<?php
						/* If the user is logged in */
						if ($logged == "in") {
							/* If this is the user that posted this thread, or the user is an admin or a moderator */
							if ($row['starter_id'] == $_SESSION['user_id'] || $_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3) {
								/* This user can remove the thread */
								echo '<a href="remove_thread.php?tid='.$row['thread_id'].'">Remove</a>';
							}
						}
					?>
				</td>
			</tr>
		<?php
			}

			$threadRes = null;

			/* If the user is logged in he/she can post a new thread */
			if ($logged == "in") {
		?>
			<tr>
				<td>
					<a href="add_thread.php?cid=<?php echo $categoryID; ?>">Add thread</a>
				</td>
			</tr>
		<?php
			}
		?>
			</tbody>
		</table>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>