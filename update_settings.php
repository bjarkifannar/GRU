<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Variables */
	$userID = null;

	/* Set the page name for the title */
	$pageName = "Update Settings";
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

			/* If the user is logged out, then redirect to the index */
			if ($logged == "out") {
				header('Location: index.php');
			} else {
				/* Get the user ID */
				$userID = $_SESSION['user_id'];
			}

			/* If the update button was pressed */
			if (isset($_POST['update'])) {
				/* Get the new values */
				$showName = $_POST['show_name_dropdown'];
				$showGender = $_POST['show_gender_dropdown'];
				$showEmail = $_POST['show_email_dropdown'];

				/* Update the database */
				$updateSettingsQuery = "UPDATE user_settings SET show_name=:show_name, show_gender=:show_gender, show_email=:show_email WHERE user_id=:user_id";
				$updateSettingsRes = $db->prepare($updateSettingsQuery);
				$updateSettingsRes->bindParam(':show_name', $showName);
				$updateSettingsRes->bindParam(':show_gender', $showGender);
				$updateSettingsRes->bindParam(':show_email', $showEmail);
				$updateSettingsRes->bindParam(':user_id', $userID);
				$updateSettingsRes->execute();
				$updateSettingsRes = null;
			}

			/* Get the user settings */
			$userSettingsQuery = "SELECT show_name, show_gender, show_email FROM user_settings WHERE user_id=:user_id";
			$userSettingsRes = $db->prepare($userSettingsQuery);
			$userSettingsRes->bindParam(':user_id', $userID);
			$userSettingsRes->execute();

			while ($row = $userSettingsRes->fetch(PDO::FETCH_ASSOC)) {
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<table class="user-settings-table">
				<thead>
					<tr>
						<th>
							<h2>Update Settings</h2>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<p><b>Show Name:</b></p>
						</td>
						<td>
							<select name="show_name_dropdown">
								<?php
									/* If show_name is 1 (true) */
									if ($row['show_name'] == 1) {
										/* Set the selected option to yes */
								?>
								<option value="1" selected>Yes</option>
								<option value="0">No</option>
								<?php
									} else {
										/* Set the selected option to no */
								?>
								<option value="1">Yes</option>
								<option value="0" selected>No</option>
								<?php
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<p><b>Show Gender:</b></p>
						</td>
						<td>
							<select name="show_gender_dropdown">
								<?php
									/* If show_gender is 1 (true) */
									if ($row['show_gender'] == 1) {
										/* Set the selected option to yes */
								?>
								<option value="1" selected>Yes</option>
								<option value="0">No</option>
								<?php
									} else {
										/* Set the selected option to no */
								?>
								<option value="1">Yes</option>
								<option value="0" selected>No</option>
								<?php
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<p><b>Show Email:</b></p>
						</td>
						<td>
							<select name="show_email_dropdown">
								<?php
									/* If show_email is 1 (true) */
									if ($row['show_email'] == 1) {
										/* Set the selected option to yes */
								?>
								<option value="1" selected>Yes</option>
								<option value="0">No</option>
								<?php
									} else {
										/* Set the selected option to no */
								?>
								<option value="1">Yes</option>
								<option value="0" selected>No</option>
								<?php
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" name="update" value="Update">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
			}

			$userSettingsRes = null;

			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>