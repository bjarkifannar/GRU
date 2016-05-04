<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Profile";

	$fb_link = null;
	$twitter_link = null;
	$st_link = null;
	$twitch_link = null;
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

			/* Get the users information */
			$username = $_SESSION['username'];
			$user_id = $_SESSION['user_id'];

			$userQuery = "SELECT users.name AS user_name,
									users.email AS user_email,
									users.register_time AS register_time,
									genders.gender AS user_gender,
									roles.role AS user_role
										FROM users
											INNER JOIN genders
												ON genders.id=users.gender_id
											INNER JOIN roles
												ON roles.id=users.role_id
										WHERE users.username=:username
											LIMIT 1";
			$userRes = $db->prepare($userQuery);
			$userRes->bindParam(':username', $username);
			$userRes->execute();

			/* Fetch the user information */
			while ($row = $userRes->fetch(PDO::FETCH_ASSOC)) {
		?>
		<table class="profile-table" align="center">
			<thead>
				<tr>
					<th colspan="2">
						<h2><?php echo $username; ?></h2>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p><b>Name:</b></p>
					</td>
					<td>
						<p><?php echo $row['user_name']; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><b>Email:</b></p>
					</td>
					<td>
						<p><?php echo $row['user_email']; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><b>Gender:</b></p>
					</td>
					<td>
						<p><?php echo $row['user_gender']; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><b>Role:</b></p>
					</td>
					<td>
						<p><?php echo $row['user_role']; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><b>Register time:</b></p>
					</td>
					<td>
						<p><?php echo $row['register_time']; ?></p>
					</td>
				</tr>
				<?php
					$social_query = "SELECT fb_link, twitter_link, st_link, twitch_link FROM user_social WHERE user_id = :user_id";
					$social_res = $db->prepare($social_query);
					$social_res->bindParam(':user_id', $user_id);
					$social_res->execute();

					$num_rows = $social_res->rowCount();

					while ($row = $social_res->fetch(PDO::FETCH_ASSOC)) {
						$fb_link = $row['fb_link'];
						$twitter_link = $row['twitter_link'];
						$st_link = $row['st_link'];
						$twitch_link = $row['twitch_link'];
					}
					echo "<tr><td><p><b>Social Links:</b></p></td><td>";
					if (!is_null($fb_link) && $fb_link != "") {echo "<a href=".$fb_link." target=\"_blank\"><img src='img/facebook-icon.png'></a>";}
					if (!is_null($twitter_link) && $twitter_link != "") {echo "<a href=".$twitter_link." target=\"_blank\"><img src='img/twitter-icon.png'></a>";}
					if (!is_null($st_link) && $st_link != "") {echo "<a href=".$st_link." target=\"_blank\"><img src='img/steam-icon.jpg'></a>";}
					if (!is_null($twitch_link) && $twitch_link != "") {echo "<a href=".$twitch_link." target=\"_blank\"><img src='img/twitch-icon.png'></a>";}
					echo "</td></tr>";


					$social_res = null;
				?>
				<tr>
					<td colspan="2"><a href="update_user.php">Update User Info</a></td>
				</tr>
			</tbody>
		</table>
			<?php 
				$image_query = "SELECT id, profile_img FROM users WHERE id = :user_id";
					$imageRes = $db->prepare($image_query);
					$imageRes->bindParam(':user_id', $user_id);
					$imageRes->execute();
			?>
			<table class="user-image-table">
				<tbody>
				<?php
					while ($row = $imageRes->fetch(PDO::FETCH_ASSOC)) {
						if (is_null($row['profile_img'])) {
							echo "<tr><td class='user-image' align='center'><img src=img/default-user-image.png width='200' height='200'/></td></tr>";
						}
						else {
							echo "<tr><td class='user-image' align='center'><img src=img/".$row['profile_img']." width='200' height='200'/></td></tr>";
						}
					}
				?>
				<tr align="center"><td><a href="upload_profile_img.php">Here you change you profile picture</a></td></tr>
				</tbody>
			</table>
			<!--<tr><td><a href=".$row['fb_link']"><img src="img/facebook-icon.png"></a></td></tr>-->
			<table>
			</table>
		<?php
			}

			/* Empty the userRes variable to free memory */
			$userRes = null;
			$imageRes = null;
		?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>