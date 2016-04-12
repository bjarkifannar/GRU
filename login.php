<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	require_once 'core/functions.php';
	
	/* Set the page name for the title */
	$pageName = "Login";
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

			/* If the user is already logged in */
			if ($logged == "in") {
				/* Go back to the index */
				header('Location: index.php');
			} else {
				/* If all the required fields are filled in */
				if (isset($_POST['email'], $_POST['p'])) {
					/* Filter and sanitize the input */
					$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
					$email = filter_var($email, FILTER_VALIDATE_EMAIL);
					$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

					/* Get the login message */
					$loginMessage = login($email, $password, $db);

					/* If the login was successful */
					if ($loginMessage == "Success") {
						/* Redirect the user to the index */
						header('Location: index.php');
					} else if ($loginMessage == "Fail") {
						/* Let the user know that there was an error */
						echo '<h2 align="center">An unknown error occurred.</h2>';
					} else if ($loginMessage == "Invalid Password") {
						/* Let the user know the password is invalid */
						echo '<h2 align="center">The password you entered does not match our records.</h2>';
					} else if ($loginMessage == "Banned") {
						/* Get the date that the ban expires */
						$selectUserBanQuery = "SELECT banned_until FROM users WHERE email=:email LIMIT 1";
						$selectUserBanRes = $db->prepare($selectUserBanQuery);
						$selectUserBanRes->bindParam(':email', $_POST['email']);
						$selectUserBanRes->execute();

						while ($row = $selectUserBanRes->fetch(PDO::FETCH_ASSOC)) {
							/* Let the user know they have been banned */
							echo '<h2 align="center">You have been banned until '.$row['banned_until'].'</h2>';
							echo '<img src="img/banned.gif" alt="You have been BANNED! HAHA!" align="center">';
						}

						$selectUserBanRes = null;
					}
				}
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" accept-charset="UTF-8">
			<table class="login-table" align="center">
				<thead>
					<tr>
						<th colspan="2">
							<h2>Login</h2>
						</th>
					</tr>
				</thead>
				<tbody>
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
							<label for="password">Password:*</label>
						</td>
						<td>
							<input type="password" name="password" id="login-password" required>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" value="Login" id="login-button" onclick="formhash(this.form, this.form.password);">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<a href="register.php">If you don't have an account you can register here.</a>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
			}

			/* Require the footer */
			require_once 'inc/footer.php';
		?>
		<script type="text/javascript" src="js/sha512.js"></script>
		<script type="text/javascript" src="js/forms.js"></script>
	</body>
</html>