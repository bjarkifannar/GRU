<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Home";

	/* Prepare to fetch the forums */
	$forumQuery = "SELECT id, forum_name FROM forums ORDER BY forum_name ASC";
	$forumRes = $db->prepare($forumQuery);
	$forumRes->execute();
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
		<table class="forum-list-table">
			<tbody>
				<tr>
					<td>
						<h3>-Latest Solved-</h3>
					</td>
				</tr>
				<tr>
					<td>
						<h3>-Latest Unsolved-</h3>
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