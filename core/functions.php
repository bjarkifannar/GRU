<?php
	/* Starts a secure session */
	function sec_session_start() {
		$sessionName = 'sec_session_id';
		$secure = false; /* false is for development ONLY! */
		$httponly = true;

		/* If the session is not secure */
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
			/* Show an error and exit */
			header('Location: error.php/Could not start a secure session');
			exit();
		}

		/* Set up cookie parameters */
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams["lifetime"],
			$cookieParams["path"],
			$cookieParams["domain"],
			$secure,
			$httponly);

		/* Set the session name */
		session_name($sessionName);

		/* Start the session and regenerate the session id */
		session_start();
		session_regenerate_id(true);
	}

	/* Login function */
	function login($email, $password, $db) {
		$loginQuery = "SELECT id, username, password, salt, role_id FROM users WHERE email=:email LIMIT 1";
		$loginRes = $db->prepare($loginQuery);
		$loginRes->bindParam(':email', $email);
		$loginRes->execute();

		/* Fetch the user information */
		while ($row = $loginRes->fetch(PDO::FETCH_ASSOC)) {
			/* Get the password */
			$salt = $row['salt'];
			$password = hash('sha512', $password.$salt);

			/* If the password matches */
			if ($row['password'] == $password) {
				$userBrowser = $_SERVER['HTTP_USER_AGENT'];

				/* Set the session variables */
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['login_string'] = hash('sha512', $password.$userBrowser);
				$_SESSION['role_id'] = $row['role_id'];

				/* Login successful */
				return true;
			} else {
				/* Login failed */
				return false;
			}
		}

		/* Login failed */
		return false;
	}

	/* Checks if the user is logged in */
	function login_check() {
		/* If the session variables are set */
		if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
			/* The user is logged in */
			return true;
		} else {
			/* The user is not logged in */
			return false;
		}
	}
?>