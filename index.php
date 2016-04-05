<?php
	require_once 'core/db_connect.php';
	
	$pageName = "Home";

	$forumQuery = "SELECT id, forum_name FROM forums";
	$forumRes = $db->prepare($forumQuery);
	$forumRes->execute();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once 'inc/head.php'; ?>
	</head>
	<body>
		<?php require_once 'inc/header.php'; ?>
		<table class="forum-list-table">
			<thead>
				<tr>
					<th>
						<h2>Forums:</h2>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					while ($row = $forumRes->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr>
					<td>
						<h3><?php echo $row['forum_name']; ?></h3>
					</td>
				</tr>
				<?php
					}

					$forumRes = null;
				?>
			</tbody>
		</table>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>