<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Profile";
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
				<tr>
					<td colspan="2"><a href="update_user.php">Update User Info</a></td>
				</tr>
			</tbody>
		</table>
		<?php
			}

			/* Empty the userRes variable to free memory */
			$userRes = null;
		?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>