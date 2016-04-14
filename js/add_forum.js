var w = $(document).width();
var min_width = 960;

function SetAddForum() {
	if (w > min_width) {
		if ($(document).scrollTop() > 76) {
			$('.add-forum-left').css("top", 0);
		} else {
			$('.add-forum-left').css("top", "5em");
		}
	} else {
		$('.add-forum-left').css("top", 0);
	}
}

$(function() {
	var num_categories = 1;
	var max_categories = 10;

	$('#add_cat_btn').on('click', function() {
		/* Add 1 to the number of categories */
		num_categories += 1;

		/* If the admin can still add more categories */
		if (num_categories <= max_categories) {
			/* Add the html */
			$('.category-checkboxes').append('<input type="checkbox" name="cat_check[]" value="' + num_categories + '" checked> ' + num_categories);
			$('.add-forum-div').append('<div class="add-forum-category-div"></div>');
			$('.add-forum-category-div:last-child').append('<h3>' + num_categories + '.</h3>');
			$('.add-forum-category-div:last-child').append('<input type="text" name="cat_' + num_categories + '_name" placeholder="Category ' + num_categories + ' name *" required><br><br>');
			$('.add-forum-category-div:last-child').append('<textarea name="cat_' + num_categories + '_desc" placeholder="Category ' + num_categories + ' description *" rows="10" cols="50" required></textarea><br><br>');
		} else {
			/* Tell the admin he can't add more categories */
			alert("You cannot add more than " + max_categories + " categories at this time.");
		}
	});
});

$(window).on('resize', function() {
	w = $(document).width();

	SetAddForum();
});

$(window).on('scroll', function() {
	SetAddForum();
});