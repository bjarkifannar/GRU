<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	/* If there is no forum id then go back to the index, else get the id */
	if (!isset($_GET['id'])) {
		header('Location: index.php');
	} else {
		$forumID = $_GET['id'];
	}
	
	/* Set the page name for the title */
	$pageName = "Forum";

	/* Prepare to fetch the categories of this forum */
	$categoryQuery = "SELECT id, category_name, category_desc FROM categories WHERE forum_id=:forum_id";
	$categoryRes = $db->prepare($categoryQuery);
	$categoryRes->bindParam(':forum_id', $forumID);
	$categoryRes->execute();
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
						<h3><?php echo $row['category_name']; ?></h3>
						<p><?php echo $row['category_desc']; ?></p>
					</td>
				</tr>
				<?php
					}

					/* Set the category result variable to null to free memory */
					$categoryRes = null;
				?>
			</tbody>
		</table>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>