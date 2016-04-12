<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* User ID */
	$uid = null;

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
					<th colspan="2">
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
				?>
			</tbody>
		</table>
		<?php
			}

			$userRes = null;

			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>