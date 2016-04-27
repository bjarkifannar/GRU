$(function() {
	$('.menu-trigger').click(function() {
  		$(this).toggleClass('expanded').siblings('div').slideToggle();
	});
});