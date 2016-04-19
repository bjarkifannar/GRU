<?php
	/* Get the required files */
	require_once 'db_connect.php';
	require_once 'functions.php';

	/* Start a secure session */
	sec_session_start();

	/* If all the required fields are filled in */
	if (isset($_POST['email'], $_POST['p'])) {
		/* Filter and sanitize the input */
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

		/* If the login was successful */
		if (login($email, $password, $db) == true) {
			/* Redirect the user to the index */
			header('Location: ../index.php');
		} else {
			/* Redirect the user to the login page */
			header('Location: ../login.php');
		}
	} else {
		/* Redirect the user to the login page */
		header('Location: ../login.php');
	}

	/* Redirect the user to the index */
	header('Location: ../index.php');
?>