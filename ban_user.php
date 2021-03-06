<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	/* The user to be banned */
	$banUserID = null;
	
	/* Set the page name for the title */
	$pageName = "Ban User";
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			/* Require the head */
			require_once 'inc/head.php';

			/* If the user is not logged in then redirect to the index */
			if ($logged == "out") {
				header('Location: index.php');
			} else if ($_SESSION['role_id'] == 1) {
				/* If the user is not a moderator or an admin then redirect to the index */
				header('Location: index.php');
			}

			/* If the user ID is not set then redirect to the index */
			if (!isset($_GET['uid'])) {
				header('Location: index.php');
			} else {
				/* Get the user ID */
				$banUserID = $_GET['uid'];
			}
		?>
	</head>
	<body>
		<?php
			/* Require the header */
			require_once 'inc/header.php';

			/* Get the user's information */
			$selectUserQuery = "SELECT username FROM users WHERE id=:user_id LIMIT 1";
			$selectUserRes = $db->prepare($selectUserQuery);
			$selectUserRes->bindParam(':user_id', $banUserID);
			$selectUserRes->execute();

			while ($row = $selectUserRes->fetch(PDO::FETCH_ASSOC)) {
				/* Only show the form if it has not been submitted */
				if (!isset($_POST['save'])) {
		?>
		<h2 align="center">Ban <?php echo $row['username']; ?></h2>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<p>Ban until: *</p>
			<input type="date" name="ban_until" required><br><br>
			<input type="text" name="ban_reason" placeholder="Reason for ban"><br><br>
			<input type="submit" name="save" value="Ban user">
			<p>* Required field.</p>
		</form>
		<?php
				} else {
		?>
		<h2 align="center">You have banned <?php echo $row['username']; ?> until <?php echo $_POST['ban_until']; ?></h2>
		<p align="center">Reason for ban: <?php echo $_POST['ban_reason']; ?></p>
		<?php
				}
			}

			$selectUserRes = null;

			/* If the form has been posted */
			if (isset($_POST['save'])) {
				/* Get the values */
				$banUntil = $_POST['ban_until'];
				$banReason = $_POST['ban_reason'];

				/* Sanitize the reason */
				$banReason = filter_var($banReason, FILTER_SANITIZE_STRING);

				/* Update the database */
				$updateBanQuery = "UPDATE users SET banned_until=:ban_until, ban_reason=:ban_reason WHERE id=:user_id";
				$updateBanRes = $db->prepare($updateBanQuery);
				$updateBanRes->bindParam(':ban_until', $banUntil);
				$updateBanRes->bindParam(':ban_reason', $banReason);
				$updateBanRes->bindParam(':user_id', $banUserID);
				$updateBanRes->execute();
				$updateBanRes = null;
			}

			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>