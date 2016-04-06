<?php
	/* Require the functions file */
	require_once 'core/functions.php';

	/* Start a secure session */
	sec_session_start();

	/* Empty the session and delete the cookie */
	$_SESSION = array();
	$params = session_get_cookie_params();

	setcookie(session_name(),
		'', time() - 42000,
		$params["path"],
		$params["domain"],
		$params["secure"],
		$params["httponly"]);
	session_destroy();

	/* Redirect to the index */
	header('Location: index.php');
?>