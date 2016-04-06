<?php
	/* Error message variable */
	$errorMessage = '';

	/* If all the required fields are filled in */
	if (isset($_POST['name'], $_POST['username'], $_POST['gender'], $_POST['email'], $_POST['p'])) {
		/* Sanitize and validate the data */
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		$gender = $_POST['gender'];

		/* If the email is not valid */
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			/* Add to the error message string */
			$errorMessage .= '<p class="error-msg">The email you entered does not appear to be valid.</p>';
		}

		/* Filter the password */
		$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

		/* If the password is not 128 characters */
		if (strlen($password) != 128) {
			/* The password is not hashed */
			$errorMessage .= '<p class="error-msg">The password could not be hashed.</p>';
		}

		/* Check if the email is in use */
		$emailQuery = "SELECT id FROM users WHERE email=:email LIMIT 1";
		$emailRes = $db->prepare($emailQuery);
		$emailRes->bindParam(':email', $email);
		$emailRes->execute();

		if ($emailRes->rowCount() > 0) {
			/* This email is already in use */
			$errorMessage .= '<p class="error-msg">The email you entered is already in use.</p>';
		}

		$emailRes = null;

		/* Check if the gender is valid */
		$genderQuery = "SELECT gender FROM genders WHERE id=:gender_id LIMIT 1";
		$genderRes = $db->prepare($genderQuery);
		$genderRes->bindParam(':gender_id', $gender);
		$genderRes->execute();

		if ($genderRes->rowCount() == 0) {
			/* The gender is not valid */
			$errorMessage .= '<p class="error-msg">The gender you picked does not match any gender in our database.</p>';
		}

		/* If there are no errors */
		if (empty($errorMessage)) {
			/* Create a random salt */
			$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

			/* Encrypt the password */
			$password = hash('sha512', $password.$randomSalt);

			/* Add the user to the database */
			$addUserQuery = "INSERT INTO users (name, username, gender_id, email, password, salt)
										VALUES (:name, :username, :gender_id, :email, :password, :salt)";
			$addUserRes = $db->prepare($addUserQuery);
			$addUserRes->bindParam(':name', $name);
			$addUserRes->bindParam(':username', $username);
			$addUserRes->bindParam(':gender_id', $gender);
			$addUserRes->bindParam(':email', $email);
			$addUserRes->bindParam(':password', $password);
			$addUserRes->bindParam(':salt', $randomSalt);

			if (!$addUserRes->execute()) {
				$errorMessage .= '<p class="error-msg>Could not add you to the database.</p>"';
			}

			$addUserRes = null;

			/* Get the user id */
			$userID = null;

			$userIdQuery = "SELECT id FROM users WHERE username=:username LIMIT 1";
			$userIdRes = $db->prepare($userIdQuery);
			$userIdRes->bindParam(':username', $username);
			$userIdRes->execute();

			while ($row = $userIdRes->fetch(PDO::FETCH_ASSOC)) {
				$userID = $row['id'];
			}

			$userIdRes = null;

			/* Add the user to the user_settings table */
			$addUserSettingsQuery = "INSERT INTO user_settings (user_id) VALUES (:user_id)";
			$addUserSettingsRes = $db->prepare($addUserSettingsQuery);
			$addUserSettingsRes->bindParam(':user_id', $userID);
			$addUserSettingsRes->execute();
			$addUserSettingsRes = null;
		}
	}
?>