<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* User ID */
	$uid = null;
	$fb_link = null;
	$twitter_link = null;
	$st_link = null;
	$twitch_link = null;

	/* Set the page name for the title */
	$pageName = "User";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If the user id is not set then redirect to the index */
			if (!isset($_GET['uid'])) {
				header('Location: index.php');
			} else {
				/* Store the user ID */
				$uid = $_GET['uid'];
			}
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';

			/* Get the user's info */
			$userQuery = "SELECT users.name AS user_name,
									users.username AS username,
									users.email AS user_email,
									users.register_time AS register_time,
									users.banned_until AS banned_until,
									users.ban_reason AS ban_reason,
									user_settings.show_name AS show_name,
									user_settings.show_gender AS show_gender,
									user_settings.show_email AS show_email,
									genders.gender AS user_gender,
									roles.role AS user_role
								FROM users
									INNER JOIN user_settings
										ON users.id=user_settings.user_id
									INNER JOIN genders
										ON users.gender_id=genders.id
									INNER JOIN roles
										ON users.role_id=roles.id
								WHERE users.id=:user_id
									LIMIT 1";
			$userRes = $db->prepare($userQuery);
			$userRes->bindParam(':user_id', $uid);
			$userRes->execute();

			while ($row = $userRes->fetch(PDO::FETCH_ASSOC)) {
		?>
		<table class="user-table">
			<thead>
				<tr>
						<?php
							$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

							$image_query = "SELECT profile_img FROM users WHERE id = :id";
							$imageRes = $db->prepare($image_query);
							$imageRes->bindParam(':id', $uid);
							$imageRes->execute();

							while ($row3 = $imageRes->fetch(PDO::FETCH_ASSOC)) {
								if (is_null($row3['profile_img'])) {
									echo "<th class='user-image'><img src=img/default-user-image.png width='150' height='150'/></th>";
								}
								else {
									echo "<th class='user-image'><img src=img/".$row3['profile_img']." width='150' height='150'/></th>";
								}
							}

							$imageRes = null;
						?>
					<th>
						<h2><?php echo $row['username']; ?></h2>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p><b>Name:</b></p>
					</td>
					<td>
						<?php
							if ($row['show_name'] == 1) {
								echo '<p>'.$row['user_name'].'</p>';
							} else {
								if ($logged == "in") {
									if ($_SESSION['role_id'] == 3) {
										echo '<p>'.$row['user_name'].'</p>';
									} else {
										echo '<p>You do not have permission to see this.</p>';
									}
								} else {
									echo '<p>You do not have permission to see this.</p>';
								}
							}
						?>
					</td>
				</tr>
				<tr>
					<td>
						<p><b>Email:</b></p>
					</td>
					<td>
						<?php
							if ($row['show_email'] == 1) {
								echo '<p>'.$row['user_email'].'</p>';
							} else {
								if ($logged == "in") {
									if ($_SESSION['role_id'] == 3) {
										echo '<p>'.$row['user_email'].'</p>';
									} else {
										echo '<p>You do not have permission to see this.</p>';
									}
								} else {
									echo '<p>You do not have permission to see this.</p>';
								}
							}
						?>
					</td>
				</tr>
				<tr>
					<td>
						<p><b>Gender:</b></p>
					</td>
					<td>
						<?php
							if ($row['show_gender'] == 1) {
								echo '<p>'.$row['user_gender'].'</p>';
							} else {
								if ($logged == "in") {
									if ($_SESSION['role_id'] == 3) {
										echo '<p>'.$row['user_gender'].'</p>';
									} else {
										echo '<p>You do not have permission to see this.</p>';
									}
								} else {
									echo '<p>You do not have permission to see this.</p>';
								}
							}
						?>
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
				<tr>
					<td>
						<p><b>Role:</b></p>
					</td>
					<td>
						<p><?php echo $row['user_role']; ?></p>
					</td>
				</tr>
				<?php
					$numThreadsQuery = "SELECT COUNT(id) AS num_threads FROM threads WHERE starter=:starter";
					$numThreadsRes = $db->prepare($numThreadsQuery);
					$numThreadsRes->bindParam(':starter', $uid);
					$numThreadsRes->execute();

					while ($row4 = $numThreadsRes->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr>
					<td>
						<p><b>Number of threads:</b></p>
					</td>
					<td>
						<p><?php echo $row4['num_threads']; ?></p>
					</td>
				</tr>
				<?php
					}

					$numThreadsRes = null;
					
					$numPostsQuery = "SELECT COUNT(id) AS num_posts FROM posts WHERE posted_by=:posted_by";
					$numPostsRes = $db->prepare($numPostsQuery);
					$numPostsRes->bindParam(':posted_by', $uid);
					$numPostsRes->execute();

					while ($row5 = $numPostsRes->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr>
					<td>
						<p><b>Number of posts:</b></p>
					</td>
					<td>
						<p><?php echo $row5['num_posts']; ?></p>
					</td>
				</tr>
				<?php
					}

					$numPostsRes = null;

					$social_query = "SELECT fb_link, twitter_link, st_link, twitch_link FROM user_social WHERE user_id = :user_id";
					$social_res = $db->prepare($social_query);
					$social_res->bindParam(':user_id', $uid);
					$social_res->execute();

					$num_rows = $social_res->rowCount();

					while ($row2 = $social_res->fetch(PDO::FETCH_ASSOC)) {
						$fb_link = $row2['fb_link'];
						$twitter_link = $row2['twitter_link'];
						$st_link = $row2['st_link'];
						$twitch_link = $row2['twitch_link'];
					}
					echo "<tr class='social-links'><td><p><b>Social Links:</b></p></td><td>";
					if (!is_null($fb_link) && $fb_link != "") {echo "<a href=".$fb_link." target=\"_blank\"><img src='img/facebook-icon.png'></a>";}
					if (!is_null($twitter_link) && $twitter_link != "") {echo "<a href=".$twitter_link." target=\"_blank\"><img src='img/twitter-icon.png'></a>";}
					if (!is_null($st_link) && $st_link != "") {echo "<a href=".$st_link." target=\"_blank\"><img src='img/steam-icon.jpg'></a>";}
					if (!is_null($twitch_link) && $twitch_link != "") {echo "<a href=".$twitch_link." target=\"_blank\"><img src='img/twitch-icon.png'></a>";}
					echo "</td></tr>";

					$social_res = null;

					/* If this user is logged in as an admin he/she can see banned_until and the ban_reason
					 * and can also ban this user */
					if ($logged == "in") {
						if ($_SESSION['role_id'] == 3) {
				?>
				<tr>
					<td>
						<p><b>Banned until:</b></p>
					</td>
					<td>
						<p><?php echo $row['banned_until']; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><b>Ban reason:</b></p>
					</td>
					<td>
						<p><?php echo $row['ban_reason']; ?></p>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<a href="ban_user.php?uid=<?php echo $uid; ?>">Ban user</a>
					</td>
				</tr>
			<?php
						}
					}
				}

				$userRes = null;

				if ($logged == "in") {
					/* If this user is an admin, he/she can change the role */
					if ($_SESSION['role_id'] == 3) {
						/* If the role is being changed */
						if (isset($_POST['change_role'])) {
							/* Get the new role */
							$newRoleID = $_POST['new_role'];

							/* Update the user's role */
							$updateRoleQuery = "UPDATE users SET role_id=:role_id WHERE id=:user_id";
							$updateRoleRes = $db->prepare($updateRoleQuery);
							$updateRoleRes->bindParam(':role_id', $newRoleID);
							$updateRoleRes->bindParam(':user_id', $uid);
							$updateRoleRes->execute();
							$updateRoleRes = null;

							/* Let the admin know the user's role has been updated */
			?>
			<h2 align="center">Role updated successfully!</h2>
			<?php
						}

						/* Get the roles */
						$selectRolesQuery = "SELECT id, role FROM roles";
						$selectRolesRes = $db->prepare($selectRolesQuery);
						$selectRolesRes->execute();
			?>
				<tr>
					<td>
						<p><b>Change role:</b></p>
					</td>
					<td>
						<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
							<select name="new_role">
							<?php
								while ($row = $selectRolesRes->fetch(PDO::FETCH_ASSOC)) {
									echo '<option value="'.$row['id'].'">'.$row['role'].'</option>';
								}

								$selectRolesRes = null;
							?>
							</select>
							<input type="submit" name="change_role" value="Change">
						</form>
					</td>
				</tr>
			<?php
					}
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