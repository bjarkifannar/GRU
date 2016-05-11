<?php
	/* Get a database connection */
	require_once 'core/db_connect.php';
	
	/* Set the page name for the title */
	$pageName = "About";
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
		?>
			<table class="forum-list-table">
				<tbody>
					<tr>
						<td>
							<h2 class="aboutTitle">-About Us: Creators-</h3>
						</td>
					</tr>
					<tr>
						<td class="aboutSpace">
							<h3 class="aboutTitle">-Bjarki Fannar Snorrason-</h3>
							<img class="aboutIMG" src="./img/BFS.png">
							<p class="aboutPara">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
								when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into 
								electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, 
								and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
						</td>
					</tr>
					<tr>
						<td class="aboutSpace">
							<h3 class="aboutTitle">-Guðlaugur Kjartan Þorgeirsson-</h3>
							<img class="aboutIMG" src="./img/GKT.png">
							<p class="aboutPara">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
								when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into 
								electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, 
								and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
						</td>
					</tr>
					<tr>
						<td class="aboutSpace">
							<h3 class="aboutTitle">-Styrmir Óli Þorsteinsson-</h3>
							<img class="aboutIMG" src="./img/SOT.png">
							<p class="aboutPara">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
								when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into 
								electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, 
								and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
						</td>
					</tr>
				</tbody>
			</table>
		<?php
			/* Require the footer */
			require_once 'inc/footer.php';
		?>
	</body>
</html>