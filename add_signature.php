<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Add Signature";
	$user_id = null;
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
			if ($logged == "out") {
				header('Location: index.php');
			}
			else {
				$user_id = $_SESSION['user_id'];
			}
		?>
		<?php
			if (isset($_POST['submit'])) {
				$signature = $_POST['editor'];
				/* Create a DOMDocument item */
				$dom = new DOMDocument();

				/* Load the HTML and get all the script tags */
				$dom->loadHTML($signature);
				$scriptTags = $dom->getElementsByTagName('script');
				$length = $scriptTags->length;

				/* Remove each tag from the DOM */
				for ($i = 0; $i < $length; $i++) {
					$scriptTags->item($i)->parentNode->removeChild($scriptTags->item($i));
				}

				$imgTags = $dom->getElementsByTagName('img');
				$imgLength = $imgTags->length;

				/* Remove the style attribute from the img tags */
				for ($i = 0; $i < $imgLength; $i++) {
					$imgTags->item($i)->removeAttribute('style');
				}

				/* Get the new html */
				$newContent = $dom->saveHTML();

				$signatureQuery = "UPDATE users SET signature = :signature WHERE id = :user_id";
				$signatureRes = $db->prepare($signatureQuery);
				$signatureRes->bindParam(':signature', $newContent);
				$signatureRes->bindParam(':user_id', $user_id);
				$signatureRes->execute();

				$signatureRes = null;

				echo '<h2 align="center">Added Signature</h2>';
			}
		?>
		<table class="add-signature-table">
			<tr>
			<td>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<br>
					<textarea name="editor" id="signature_editor"></textarea>
					<input type="submit" name="submit" value="Submit" class="add-thread-button">
				</form>
			</td>
			</tr>
		</table>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
		<script src="http://cdn.ckeditor.com/4.5.8/standard/ckeditor.js"></script>
		<script type="text/javascript">
			/* Replace the textarea with the CKEditor */
			CKEDITOR.replace('signature_editor', {
				/* Remove the source button */
				removeButtons: 'Source',
			});
		</script>
	</body>
</html>