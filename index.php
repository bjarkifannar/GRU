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
						<h1>Last Post</h1>
					</td>
				</tr>
				<?php
					/* Fetch the forums and show them */
					while ($row = $forumRes->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr class="forum-setup">
					<td class="left-side">
						<h3><a href="forum.php?id=<?php echo $row['id']; ?>"><?php echo $row['forum_name']; ?></a></h3><div class="text-dec">This is a test.</div>
					</td>
					<td class="right-side">
						<a href="forum.php?id=<?php echo $row['id']; ?>"><?php echo $row['forum_name']; ?></a></h3>
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