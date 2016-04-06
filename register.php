<?php
	/* Get a database connection and the register include file */
	require_once 'core/db_connect.php';
	require_once 'core/register_inc.php';
	
	/* Set the page name for the title */
	$pageName = "Register";
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

			/* If there are error messages, show them */
			if (!empty($errorMessage)) {
				echo $errorMessage;
			}
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" accept-charset="UTF-8">
			<table class="register-table">
				<thead>
					<tr>
						<th colspan="2">
							<h2>Register</h2>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<label for="name">Full name:*</label>
						</td>
						<td>
							<input type="text" name="name">
						</td>
					</tr>
					<tr>
						<td>
							<label for="username">Username:*</label>
						</td>
						<td>
							<input type="text" name="username">
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
						<td>
							<label for="email">Email:*</label>
						</td>
						<td>
							<input type="text" name="email">
						</td>
					</tr>
					<tr>
						<td>
							<label for="password">Password:*</label>
						</td>
						<td>
							<input type="password" name="password">
						</td>
					</tr>
					<tr>
						<td>
							<label for="confirmpwd">Confirm password:*</label>
						</td>
						<td>
							<input type="password" name="confirmpwd">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" value="Register" onclick="return regformhash(this.form,
																								this.form.name,
																								this.form.username,
																								this.form.gender,
																								this.form.email,
																								this.form.password,
																								this.form.confirmpwd);">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
		<script type="text/javascript" src="js/sha512.js"></script>
		<script type="text/javascript" src="js/forms.js"></script>
	</body>
</html>