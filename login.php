<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Login";

	$logged = "out";
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
		?>
		<form action="core/process_login.php" method="POST" accept-charset="UTF-8">
			<table class="login-table">
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
							<input type="password" name="password" required>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" value="Login" onclick="formhash(this.form, this.form.password);">
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