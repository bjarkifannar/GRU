<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	$userID = null;
	
	$fb_link = null;
	$twitter_link = null;
	$st_link = null;
	$twitch_link = null;

	/* Set the page name for the title */
	$pageName = "Update user";
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
			} else {
				$userID = $_SESSION['user_id'];
			}
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" accept-charset="UTF-8">
			<table class="update-table" align="center">
				<thead>
					<tr>
						<th colspan="2">
							<h2>Update</h2>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<label for="name">Name:*</label>
						</td>
						<td>
							<input type="text" name="name" required>
						</td>
					</tr>
					<tr>
						<td>
							<label for="email">Email:*</label>
						</td>
						<td>
							<input type="text" name="email" required>
						</td>
					</tr>
					<tr>
						<td>
							<label for="gender">Gender:*</label>
						</td>
						<td>
							<select name="gender">
							<?php
								/* Get the genders */
								$genderQuery = "SELECT id, gender FROM genders";
								$genderRes = $db->prepare($genderQuery);
								$genderRes->execute();

								/* Show the genders */
								while ($row = $genderRes->fetch(PDO::FETCH_ASSOC)) {
							?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['gender']; ?></option>
							<?php
								}

								$genderRes = null;
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="Submit" value="update" name="submit-update">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php if(isset($_POST['submit-update'])) {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$gender_id = $_POST['gender'];
			$user_id = $_SESSION['user_id'];

			$updateQuery = "UPDATE users SET name = :name, email = :email, gender_id = :gender_id WHERE id=:id";
			$updateRes = $db->prepare($updateQuery);
			$updateRes->bindParam(':name', $name);
			$updateRes->bindParam(':email', $email);
			$updateRes->bindParam(':gender_id', $gender_id);
			$updateRes->bindParam(':id', $user_id);
			$updateRes->execute();
			$updateRes = null;
		}
		$img_dia = '20px';

		$link_query = "SELECT fb_link, twitter_link, st_link, twitch_link FROM user_social WHERE user_id = :user_id LIMIT 1";
		$link_res = $db->prepare($link_query);
		$link_res->bindParam(':user_id', $userID);
		$link_res->execute();

		while ($row = $link_res->fetch(PDO::FETCH_ASSOC)) {
			$fb_link = $row['fb_link'];
			$twitter_link = $row['twitter_link'];
			$st_link = $row['st_link'];
			$twitch_link = $row['twitch_link'];
		}

		$link_res = null;

		?>
		<table class="social-link-table">
			<tr>
				<td>
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
						<img src="img/facebook-icon.png" width="<?php echo $img_dia; ?>" height="<?php echo $img_dia; ?>">
						<input type="text" name="fb_link" value="<?php echo $fb_link; ?>">
						<input type="submit" name="fb_sub">
					</form>
				</td>
			</tr>
			<tr>
				<td>
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
						<img src="img/twitter-icon.png" width="<?php echo $img_dia; ?>" height="<?php echo $img_dia; ?>">
						<input type="text" name="twitter_link" value="<?php echo $twitter_link; ?>">
						<input type="submit" name="twitter_sub">
					</form>
				</td>
			</tr>
			<tr>
				<td>
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
						<img src="img/steam-icon.jpg" width="<?php echo $img_dia; ?>" height="<?php echo $img_dia; ?>">
						<input type="text" name="st_link" value="<?php echo $st_link; ?>">
						<input type="submit" name="st_sub">
					</form>
				</td>
			</tr>
			<tr>
				<td>
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
						<img src="img/twitch-icon.png" width="<?php echo $img_dia; ?>" height="<?php echo $img_dia; ?>">
						<input type="text" name="twitch_link" value="<?php echo $twitch_link; ?>">
						<input type="submit" name="twitch_sub">
					</form>
				</td>
			</tr>
			<tr><td><a href="upload_profile_img.php">Here you change you profile picture</a></td></tr>
		</table>
		<?php
			if (isset($_POST['fb_sub']) || isset($_POST['twitter_sub']) || isset($_POST['st_sub']) || isset($_POST['twitch_sub'])) {
				$social_query = "SELECT id FROM user_social WHERE user_id = :user_id";
				$social_res = $db->prepare($social_query);
				$social_res->bindParam(':user_id', $userID);
				$social_res->execute();

				$num_rows = $social_res->rowCount();

				$social_res = null;

				if ($num_rows == 0) {
					$social_insert = "INSERT INTO user_social (user_id) VALUES (:user_id)";
					$social_res = $db->prepare($social_insert);
					$social_res->bindParam(':user_id', $userID);
					$social_res->execute();

					$social_res = null;
				}
			}

			if (isset($_POST['fb_sub'])) {
				$fb_link = $_POST['fb_link'];
				$fb_query = "UPDATE user_social SET fb_link = :fb_link WHERE user_id = :user_id";
				$fb_res = $db->prepare($fb_query);
				$fb_res->bindParam(':fb_link', $fb_link);
				$fb_res->bindParam(':user_id', $userID);
				$fb_res->execute();

				$fb_res = null;

			}

			if (isset($_POST['twitter_sub'])) {
				$twitter_link = $_POST['twitter_link'];
				$twitter_query = "UPDATE user_social SET twitter_link = :twitter_link WHERE user_id = :user_id";
				$twitter_res = $db->prepare($twitter_query);
				$twitter_res->bindParam(':twitter_link', $twitter_link);
				$twitter_res->bindParam(':user_id', $userID);
				$twitter_res->execute();

				$twitter_res = null;

			}

			if (isset($_POST['st_sub'])) {
				$st_link = $_POST['st_link'];
				$st_query = "UPDATE user_social SET st_link = :st_link WHERE user_id = :user_id";
				$st_res = $db->prepare($st_query);
				$st_res->bindParam(':st_link', $st_link);
				$st_res->bindParam(':user_id', $userID);
				$st_res->execute();

				$st_res = null;

			}

			if (isset($_POST['twitch_sub'])) {
				$twitch_link = $_POST['twitch_link'];
				$twitch_query = "UPDATE user_social SET twitch_link = :twitch_link WHERE user_id = :user_id";
				$twitch_res = $db->prepare($twitch_query);
				$twitch_res->bindParam(':twitch_link', $twitch_link);
				$twitch_res->bindParam(':user_id', $userID);
				$twitch_res->execute();

				$twitch_res = null;

			}
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>