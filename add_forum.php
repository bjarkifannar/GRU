<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "Add Forum";
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
			}

			/* Show the forum if it has not been submitted */
			if (!isset($_POST['submit'])) {
		?>
		<form class="add-forum-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<div class="add-forum-left">
				<input type="text" name="forum_title" placeholder="Forum title *" required><br><br>
				<input type="button" name="add_cat_btn" id="add_cat_btn" value="Add Category"><br><br>
				<input type="submit" name="submit" value="Submit"><br><br>
				<div class="category-checkboxes"><input type="checkbox" name="cat_check[]" value="1" checked> 1</div>
			</div>
			<div class="add-forum-right">
				<div class="add-forum-div">
					<div class="add-forum-category-div">
						<h3>1.</h3>
						<input type="text" name="cat_1_name" placeholder="Category 1 name *" required><br><br>
						<textarea name="cat_1_desc" placeholder="Category 1 description *" rows="10" cols="50" required></textarea><br><br>
					</div>
				</div>
			</div>
		</form>
		<?php
			} else { /* If the form has been submitted */
				/* Get the information */
				$forumTitle = $_POST['forum_title'];
				$checkedCategories = $_POST['cat_check'];
				$categoryNames = array();
				$categoryDescriptions = array();
				$canAddForum = true;

				/* Put the queries in variables */
				$selectForumIDQuery = "SELECT id FROM forums WHERE forum_name=:forum_name LIMIT 1";
				$insertForumQuery = "INSERT INTO forums (forum_name) VALUES (:forum_name)";
				$insertCategoriesQuery = "INSERT INTO categories (forum_id, category_name, category_desc) VALUES (:forum_id, :category_name, :category_desc)";

				/* See what categories to add */
				for ($i = 0; $i < count($checkedCategories); $i++) {
					if (isset($_POST['cat_'.$checkedCategories[$i].'_name'])) {
						$categoryNames[] = $_POST['cat_'.$checkedCategories[$i].'_name'];
						$categoryDescriptions[] = $_POST['cat_'.$checkedCategories[$i].'_desc'];
					}
				}

				/* Check if there is already a forum with this name */
				$selectForumNameRes = $db->prepare($selectForumIDQuery);
				$selectForumNameRes->bindParam(':forum_name', $forumTitle);
				$selectForumNameRes->execute();

				/* If there is already a forum with this name */
				if ($selectForumNameRes->rowCount() > 0) {
					/* Let the admin know and don't add the forum */
					echo '<h2 align="center">There is already a forum with that name.</h2>';
					$canAddForum = false;
				}

				$selectForumNameRes = null;

				/* If the admin can add the forum */
				if ($canAddForum) {
					/* Variables */
					$forumID = null;

					/* Insert the forum */
					$insertForumRes = $db->prepare($insertForumQuery);
					$insertForumRes->bindParam(':forum_name', $forumTitle);
					$insertForumRes->execute();
					$insertForumRes = null;

					/* Get the forum's ID */
					$selectForumIDRes = $db->prepare($selectForumIDQuery);
					$selectForumIDRes->bindParam(':forum_name', $forumTitle);
					$selectForumIDRes->execute();

					while ($row = $selectForumIDRes->fetch(PDO::FETCH_ASSOC)) {
						$forumID = $row['id'];
					}

					$selectForumIDRes = null;

					/* Insert the categories */
					$insertCategoriesRes = $db->prepare($insertCategoriesQuery);
					$insertCategoriesRes->bindParam(':forum_id', $forumID);

					for ($i = 0; $i < count($categoryNames); $i++) {
						$insertCategoriesRes->bindParam(':category_name', $categoryNames[$i]);
						$insertCategoriesRes->bindParam(':category_desc', $categoryDescriptions[$i]);
						$insertCategoriesRes->execute();
					}

					$insertCategoriesRes = null;

					/* Let the admin know the forum has been added and give him/her a link to the forum */
					echo '<h2 align="center">The forum '.$forumTitle.' has been added.</h2><br>';
					echo '<a href="forum.php?id='.$forumID.'">View the forum</a>';
				}
			}

			/* Require the footer */
			require_once 'inc/footer.php';
		?>
		<script type="text/javascript" src="js/add_forum.js"></script>
	</body>
</html>