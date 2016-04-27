<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';

	$forumID = null;
	
	/* Set the page name for the title */
	$pageName = "Add Category";
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

			/* If the user is not logged in then redirect to the index */
			if ($logged == "out") {
				header('Location: index.php');
			} else if ($_SESSION['role_id'] != 3) {
				/* If this user is not an admin then redirect to the index */
				header('Location: index.php');
			} else if (!isset($_GET['fid'])) {
				/* If there is no forum ID then redirect to the index */
				header('Location: index.php');
			} else {
				/* Get the forum ID */
				$forumID = $_GET['fid'];
			}

			/* If the add button has not been pressed then show the form */
			if (!isset($_POST['add_category'])) {
		?>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<table class="add-category-table">
				<thead>
					<tr>
						<th>
							<h2>Add Category</h2>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<input type="text" name="category_title" placeholder="Title*" required>
						</td>
					</tr>
					<tr>
						<td>
							<textarea name="category_desc" placeholder="Description" rows="10" cols="50"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="add_category" value="Add">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
			} else {
				/* Add the category */
				$categoryTitle = $_POST['category_title'];

				if ($_POST['category_desc'] != "") {
					$categoryDescription = $_POST['category_desc'];

					$insertCategoryQuery = "INSERT INTO categories (forum_id, category_name, category_desc)
											VALUES (:forum_id, :category_name, :category_desc)";
					$insertCategoryRes = $db->prepare($insertCategoryQuery);
					$insertCategoryRes->bindParam(':forum_id', $forumID);
					$insertCategoryRes->bindParam(':category_name', $categoryTitle);
					$insertCategoryRes->bindParam(':category_desc', $categoryDescription);
					$insertCategoryRes->execute();
					$insertCategoryRes = null;
				} else {
					$insertCategoryQuery = "INSERT INTO categories (forum_id, category_name) VALUES (:forum_id, :category_name)";
					$insertCategoryRes = $db->prepare($insertCategoryQuery);
					$insertCategoryRes->bindParam(':forum_id', $forumID);
					$insertCategoryRes->bindParam(':category_name', $categoryTitle);
					$insertCategoryRes->execute();
					$insertCategoryRes = null;
				}

				/* Let the user know the category has been added */
				echo '<h2 align="center">The category has been added!</h2>';
			}

			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>