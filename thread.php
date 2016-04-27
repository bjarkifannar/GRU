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
			<thead>
				<tr>
					<th>
					<?php
						/* If this thread has been marked as solved */
						if (isset($_POST['mark_solved'])) {
							$solvedStatus = 1;

							$updateQuery = "UPDATE threads SET solved=:solved_status WHERE id=:thread_id LIMIT 1";
							$updateRes = $db->prepare($updateQuery);
							$updateRes->bindParam(':solved_status', $solvedStatus);
							$updateRes->bindParam(':thread_id', $threadID);
							$updateRes->execute();
							$updateRes = null;
						}

						/* If this thread has been marked as unsolved */
						if (isset($_POST['mark_unsolved'])) {
							$solvedStatus = 0;

							$updateQuery = "UPDATE threads SET solved=:solved_status WHERE id=:thread_id LIMIT 1";
							$updateRes = $db->prepare($updateQuery);
							$updateRes->bindParam(':solved_status', $solvedStatus);
							$updateRes->bindParam(':thread_id', $threadID);
							$updateRes->execute();
							$updateRes = null;
						}

						/* Get information to mark this thread as solved */
						$threadQuery = "SELECT starter, solved FROM threads WHERE id=:thread_id LIMIT 1";
						$threadRes = $db->prepare($threadQuery);
						$threadRes->bindParam(':thread_id', $threadID);
						$threadRes->execute();

						while ($row = $threadRes->fetch(PDO::FETCH_ASSOC)) {
							if ($row['solved'] == 1) {
								echo '<h2>* SOLVED *</h2>';
							}

							/* If this person is logged in */
							if ($logged == "in") {
								/* If this is the person who started this thread, an admin or a moderator */
								if ($_SESSION['user_id'] == $row['starter'] || $_SESSION['role_id'] == 3 || $_SESSION['role_id'] == 2) {
									/* If this thread is not solved */
									if ($row['solved'] == 0) {
										/* Give the option to mark this as solved */
					?>
						<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
							<input type="submit" name="mark_solved" value="Mark as Solved">
						</form>
					<?php
									} else {
										/* If this thread has been marked as solved
										 * give the option to mark it as unsolved */
					?>
						<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
							<input type="submit" name="mark_unsolved" value="Mark as Unsolved">
						</form>
					<?php
									}
								}
							}
						}

						$threadRes = null;
					?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
				/* Get the posts in this thread */
				$postQuery = "SELECT posts.id AS post_id,
										posts.post_name AS post_name,
										posts.created AS created,
										posts.revised AS revised,
										posts.post_content AS post_content,
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

							if ($logged == "in") {
								if ($row['posted_by_id'] == $_SESSION['user_id'] || $_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3) {
						?>
						<a href="remove_post.php?pid=<?php echo $row['post_id']; ?>">Remove</a>
						<?php
								}
							}
						?>
						<hr>
					</td>
				</tr>
				<tr>
					<td class="post-content">
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
			/* Users can reply if they are logged in */
			if ($logged == "in") {
				if (isset($_POST['submit_reply'])) {
					/* Get the title, the content and the user's ID */
					$replyTitle = $_POST['reply_title'];
					$replyContent = $_POST['reply_content'];
					$userID = $_SESSION['user_id'];

					/* Create a DOMDocument item */
					$dom = new DOMDocument();

					/* Load the HTML and get all the script tags */
					$dom->loadHTML($replyContent);
					$scriptTags = $dom->getElementsByTagName('script');
					$length = $scriptTags->length;

					/* Remove each tag from the DOM */
					for ($i = 0; $i < $length; $i++) {
						$scriptTags->item($i)->parentNode->removeChild($scriptTags->item($i));
					}

					/* Get the new html */
					$newContent = $dom->saveHTML();

					/* Remove all tags from the title */
					$newTitle = strip_tags($replyTitle);

					/* Insert the reply */
					$insertReplyQuery = "INSERT INTO posts (post_name, thread_id, posted_by, post_content)
													VALUES (:post_name, :thread_id, :posted_by, :post_content)";
					$insertReplyRes = $db->prepare($insertReplyQuery);
					$insertReplyRes->bindParam(':post_name', $newTitle);
					$insertReplyRes->bindParam(':thread_id', $threadID);
					$insertReplyRes->bindParam(':posted_by', $userID);
					$insertReplyRes->bindParam(':post_content', $newContent);
					$insertReplyRes->execute();
					$insertReplyRes = null;

					/* Refresh the page */
					echo "<meta http-equiv='refresh' content='0'>";
				}
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<table class="reply-table" style="width: 50%; margin: 0 auto;">
				<thead>
					<tr>
						<th>
							<h3>Reply:</h3>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<input type="text" name="reply_title" placeholder="Title" required>
						</td>
					</tr>
					<tr>
						<td>
							<textarea name="reply_content" id="content_editor" required></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="submit_reply" value="Submit">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
			}
		?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
		<script src="http://cdn.ckeditor.com/4.5.8/standard/ckeditor.js"></script>
		<script type="text/javascript">
			/* Replace the textarea with the CKEditor */
			CKEDITOR.replace('content_editor', {
				/* Remove the source button */
				removeButtons: 'Source',
			});
		</script>
	</body>
</html>