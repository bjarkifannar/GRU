<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Thread ID */
	$threadID = null;

	/* Set the page name for the title */
	$pageName = "Thread";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If the thread ID is not set then redirect to the index */
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
		?>
		<table class="thread-table" style="width: 50%; margin: 0 auto;">
			<tbody>
			<?php
				/* Get the posts in this thread */
				$postQuery = "SELECT posts.post_name AS post_name,
										posts.created AS created,
										posts.revised AS revised,
										posts.post_content AS post_content,
										posts.solved AS solved,
										posts.posted_by AS posted_by_id,
										users.username AS posted_by
											FROM posts
										INNER JOIN users
											ON posts.posted_by=users.id
										WHERE posts.thread_id=:thread_id";
				$postRes = $db->prepare($postQuery);
				$postRes->bindParam(':thread_id', $threadID);
				$postRes->execute();

				while ($row = $postRes->fetch(PDO::FETCH_ASSOC)) {
			?>
				<tr>
					<td>
						<h3 align="center"><?php echo $row['post_name']; ?></h3>
						<a href="user.php?uid=<?php echo $row['posted_by_id']; ?>"><p><b><?php echo $row['posted_by']; ?></b></p></a>
						<p><?php echo $row['created']; ?></p>
						<?php
							if ($row['revised'] != "" && $row['revised'] != NULL) {
								echo '<p>Revised: '.$row['revised'].'</p>';
							}
						?>
						<hr>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $row['post_content']; ?>
						<hr>
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