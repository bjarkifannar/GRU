<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Upload Profile Image";
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

			/* Temporary username variable */
			$tmpUsername = null;

			/* If the user is not logged in then redirect to the index */
			if ($logged == "out") {
				header('Location: index.php');
			} else {
				/* Get the username */
				$tmpUsername = $_SESSION['username'];
			}

			/* If the user submitted the file upload */
			if (isset($_POST['submit'])) {
				/* Variables */
				$errorMsg = array(); /* Error messages */
				$targetDir = "img/"; /* Directory to upload to */
				$targetFile = $targetDir.basename($_FILES["profile_img"]["name"]); /* The file path */
				$uploadOk = 1; /* Is it OK to upload this file? */
				$imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION); /* The file type */
				$maxFileSize = 500000; /* Max file size in bytes */

				/* Check if this is an image */
				$check = getimagesize($_FILES["profile_img"]["tmp_name"]);

				/* If the file is an actual image */
				if ($check !== false) {
					/* The image can be uploaded */
					$uploadOk = 1;
				} else {
					/* If the file is not an image the file can not be uploaded */
					$uploadOk = 0;
				}

				/* Check the file size */
				if ($_FILES["profile_img"]["size"] > $maxFileSize) {
					/* Add an error message and don't upload the file */
					$errorMsg[] = 'The file you tried to upload is too big.';
					$uploadOk = 0;
				}

				/* Only allow a few formats */
				if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif") {
					/* Add an error message and don't upload the file */
					$errorMsg[] = "Allowed file types are: JPG, JPEG, PNG and GIF";
					$uploadOk = 0;
				}

				/* Show error/s if there are any */
				if (!empty($errorMsg)) {
					foreach ($errorMsg as $error) {
						/* Display all the errors */
						echo '<h3 align="center" class="error-msg">'.$error.'</h3>';
					}
				} else if ($uploadOk == 1) { /* If there are no errors and the image can be uploaded */
					/* Rename the file */
					$temp = explode(".", $_FILES["profile_img"]["name"]); /* Split the file name up */
					$newFileName = round(microtime(true)).'.'.end($temp); /* Give it a new name based on the time and add the extension */

					/* If the file was uploaded with the new name */
					if(move_uploaded_file($_FILES["profile_img"]["tmp_name"], $targetDir.$newFileName)) {
						/* Put the file name in the database and let the user know */
						$updateProfileImgQuery = "UPDATE users SET profile_img=:profile_img WHERE username=:username";
						$updateProfileImgRes = $db->prepare($updateProfileImgQuery);
						$updateProfileImgRes->bindParam(':profile_img', $newFileName);
						$updateProfileImgRes->bindParam(':username', $tmpUsername);
						$updateProfileImgRes->execute();
						$updateProfileImgRes = null;

						echo '<h2 align="center">The profile picture has been uploaded.</h2>';
					}
				}
			} else {
				/* Show the form if the user has not submitted it */
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
			Select an image to upload:
			<input type="file" name="profile_img"><br>
			<input type="submit" value="Upload" name="submit">
		</form>
		<?php
			}

			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>