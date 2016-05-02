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
	$orderThreads = FALSE;
	$orderValue = null;

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

	/* If the order variable is set */
	if (isset($_GET['order'])) {
		/* If the order value is valid */
		if ($_GET['order'] == "title_asc" || $_GET['order'] == "title_desc" || $_GET['order'] == "post_time_asc" || $_GET['order'] == "post_time_desc") {
			/* Set the order value */
			$orderValue = $_GET['order'];
			$orderThreads = TRUE;
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
				<tr>
					<td>
						<?php
							if (isset($_GET['solved'])) {
								$currentURL = "category.php?cid=$categoryID&solved=$solvedValue";
							} else {
								$currentURL = "category.php?cid=$categoryID";
							}
						?>
						<p>Order by: <a href="<?php echo $currentURL; ?>&order=title_asc">Title A-Z</a> | <a href="<?php echo $currentURL; ?>&order=title_desc">Title Z-A</a> | <a href="<?php echo $currentURL; ?>&order=post_time_desc">Post Time (Newest First)</a> | <a href="<?php echo $currentURL; ?>&order=post_time_asc">Post Time (Oldest First)</a></p>
					</td>
				</tr>
		<?php
			/* If show only solved is true */
			if ($showOnlySolved) {
				/* If the threads should be ordered */
				if ($orderThreads) {
					/* If the threads should be ordered by title */
					if ($orderValue == "title_asc") {
						/* Get all the threads in this category that are solved and order them by title A-Z */
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.thread_name ASC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					} else if ($orderValue == "title_desc") {
						/* Get all the threads in this category that are solved and order them by title Z-A */
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.thread_name DESC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					} else if ($orderValue == "post_time_asc") {
						/* Get all the threads in this category that are solved and order them by post time */
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.post_time ASC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					} else if ($orderValue == "post_time_desc") {
						/* Get all the threads in this category that are solved and order them by post time */
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.post_time DESC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					}
				} else {
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
				}
			} else if ($showOnlyUnsolved) {
				if ($orderThreads) {
					if ($orderValue == "title_asc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.thread_name ASC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					} else if ($orderValue == "title_desc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.thread_name DESC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					} else if ($orderValue == "post_time_asc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.post_time ASC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					} else if ($orderValue == "post_time_desc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
														WHERE threads.category_id=:cat_id
															AND solved=:solved_value
																ORDER BY threads.post_time DESC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->bindParam(':solved_value', $solvedValue);
						$threadRes->execute();
					}
				} else {
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
				}
			} else {
				if ($orderThreads) {
					if ($orderValue == "title_asc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
													WHERE threads.category_id=:cat_id
														ORDER BY threads.thread_name ASC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->execute();
					} else if ($orderValue == "title_desc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
													WHERE threads.category_id=:cat_id
														ORDER BY threads.thread_name DESC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->execute();
					} else if ($orderValue == "post_time_asc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
													WHERE threads.category_id=:cat_id
														ORDER BY threads.post_time ASC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->execute();
					} else if ($orderValue == "post_time_desc") {
						$threadQuery = "SELECT threads.id AS thread_id,
												threads.thread_name AS thread_name,
												threads.starter AS starter_id,
												users.username AS starter_name
													FROM threads
														INNER JOIN users
															ON threads.starter=users.id
													WHERE threads.category_id=:cat_id
														ORDER BY threads.post_time DESC";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':cat_id', $categoryID);
						$threadRes->execute();
					}
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