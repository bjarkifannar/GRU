<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
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
						<td>
							<label for="password">Password:*</label>
						</td>
						<td>
							<input type="password" name="password" id="update-password" required>
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

		} ?>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>