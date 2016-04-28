<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	/* Category ID */
	$categoryID = null;
	
	/* Set the page name for the title */
	$pageName = "Add Thread";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If the category id is not set then redirect to the index */
			if (!isset($_GET['cid'])) {
				header('Location: index.php');
			} else {
				/* Get the category id */
				$categoryID = $_GET['cid'];
			}

			/* If the user is not logged in then redirect to the index */
			if ($logged == "out") {
				header('Location: index.php');
			}
		?>
		<style type="text/css">
			#thread_editor {
				width: 50%;
			}
		</style>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';
		?>
		<?php
			if (isset($_POST['submit'])) {
				/* Get the title, the content and the user's ID */
				$threadTitle = $_POST['thread_title'];
				$threadContent = $_POST['editor'];
				$userID = $_SESSION['user_id'];

				/* Create a DOMDocument item */
				$dom = new DOMDocument();

				/* Load the HTML and get all the script tags */
				$dom->loadHTML($threadContent);
				$scriptTags = $dom->getElementsByTagName('script');
				$length = $scriptTags->length;

				/* Remove each tag from the DOM */
				for ($i = 0; $i < $length; $i++) {
					$scriptTags->item($i)->parentNode->removeChild($scriptTags->item($i));
				}

				/* Get the new html */
				$newContent = $dom->saveHTML();

				/* Remove all tags from the title */
				$newTitle = strip_tags($threadTitle);

				/* Insert the thread */
				$insertThreadQuery = "INSERT INTO threads (thread_name, category_id, starter) VALUES (:thread_name, :category_id, :thread_starter)";
				$insertThreadRes = $db->prepare($insertThreadQuery);
				$insertThreadRes->bindParam(':thread_name', $newTitle);
				$insertThreadRes->bindParam(':category_id', $categoryID);
				$insertThreadRes->bindParam(':thread_starter', $userID);
				$insertThreadRes->execute();
				$insertThreadRes = null;

				/* Get the thread's ID */
				$threadID = null;

				$getThreadIDQuery = "SELECT MAX(id) AS thread_id
											FROM threads
												WHERE thread_name=:thread_name
													AND category_id=:category_id
													AND starter=:thread_starter
												LIMIT 1";
				$getThreadIDRes = $db->prepare($getThreadIDQuery);
				$getThreadIDRes->bindParam(':thread_name', $newTitle);
				$getThreadIDRes->bindParam(':category_id', $categoryID);
				$getThreadIDRes->bindParam(':thread_starter', $userID);
				$getThreadIDRes->execute();

				while ($row = $getThreadIDRes->fetch(PDO::FETCH_ASSOC)) {
					$threadID = $row['thread_id'];
				}

				$getThreadIDRes = null;

				/* Insert the post into the thread */
				$insertPostQuery = "INSERT INTO posts
												(post_name, thread_id, posted_by, post_content)
										VALUES (:post_name, :thread_id, :posted_by, :post_content)";
				$insertPostRes = $db->prepare($insertPostQuery);
				$insertPostRes->bindParam(':post_name', $newTitle);
				$insertPostRes->bindParam(':thread_id', $threadID);
				$insertPostRes->bindParam(':posted_by', $userID);
				$insertPostRes->bindParam(':post_content', $newContent);
				$insertPostRes->execute();
				$insertPostRes = null;

				/* Redirect to the post */
				header('Location: thread.php?tid='.$threadID);
			}
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" style="width: 50%; margin: 0 auto;">
			<br>
			<input type="text" name="thread_title" placeholder="Title" class="add-thread-title" required><br><br>
			<textarea name="editor" id="thread_editor"></textarea>
			<input type="submit" name="submit" value="Submit" class="add-thread-button">
		</form>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
		<script src="http://cdn.ckeditor.com/4.5.8/standard/ckeditor.js"></script>
		<script type="text/javascript">
			/* Replace the textarea with the CKEditor */
			CKEDITOR.replace('thread_editor', {
				/* Remove the source button */
				removeButtons: 'Source',
			});
		</script>
	</body>
</html>