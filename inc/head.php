<?php
	/* Require the functions file */
	require_once 'core/functions.php';

	/* Start a secure session */
	sec_session_start();

	/* If the user is logged in */
	if (login_check()) {
		$logged = "in";
	} else {
		$logged = "out";
	}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $pageName; ?></title>
<link rel="stylesheet" type="text/css" href="css/style.css">