<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<div id="search-bar">
			<form id="search" method="get" action="http://www.google.com">
			        <input type="text" class="search-text" name="q" size="21" maxlength="120">
			        <input type="submit" value="search" name="search" class="search-button">
			        <div class="search-radio">
			        	<input type="radio" name="searched" value="thread" checked>Threads
	  					<input type="radio" name="searched" value="name">Names
	  					<input type="radio" name="searched" value="post">Posts
			        </div>
			</form>
			<?PHP

				$thread_status = 'unchecked';
				$name_status = 'unchecked';
				$post_status = 'unchecked';

				if (isset($_POST['search'])) {
					$selected_radio = $_POST['searched'];

					if ($selected_radio == 'thread') {
						$thread_status = 'checked';
						alert['hallo1'];
					}
					else if ($selected_radio == 'name') {
						$name_status = 'checked';
						alert['hallo2'];
					}
					else if ($selected_radio == 'post'){
						$post_status = 'checked'
						alert['hallo3'];
					}
				}
			?>
			<div class="search-clear"></div>
		</div>
	</body>
</html>