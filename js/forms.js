/* Hash a password */
function formhash(form, password) {
	/* Create a new hidden text field for the hashed password and add it to the form */
	var p = document.createElement("input");

	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	p.value = hex_sha512(password.value);

	/* Clear the plain text password so it doesn't get sent from the device */
	password.value = "";

	/* Submit the form */
	form.submit();
}

/* Register form */
function regformhash(form, name, uid, gender, email, password, conf) {
	/* If any of the required fields are empty */
	if (name.value == ''		||
		uid.value == ''			||
		gender.value == ''		||
		email.value == ''		||
		password.value == ''	||
		conf.value == '') {
		/* Let user know and exit this function */
		alert("Please fill out all fields that have *");
		return false;
	}

	/* Check if the username has spaces or invalid symbols */
	re = /^\w+$/;
	if (!re.test(form.username.value)) {
		/* Let the user know this username is invalid */
		alert("Usernames can only have letters, numbers and underscores");
		return false;
	}

	/* Check if the password is less than 6 letters */
	if (password.value.length < 6) {
		/* Let the user know the password is too short */
		alert("Your password must be at least 6 characters long");
		return false;
	}

	/* Check if the passwords match */
	if (password.value != conf.value) {
		/* Let the user know the passwords don't match */
		alert("The passwords don't match");
		form.password.focus();
		return false;
	}

	/* Create a new hidden text field for the hashed password and add it to the form */
	var p = document.createElement("input");

	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	p.value = hex_sha512(password.value);

	/* Clear the plain text password fields */
	password.value = "";
	conf.value = "";

	/* Submit the form */
	form.submit();

	return true;
}