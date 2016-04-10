$(document).ready(function()
{
	var navKey = 'navIndex';
	var navIndex= sessionStorage.getItem(navKey);
	var $nav = $('#header nav');	

	if (typeof navIndex === 'undefined') {
		navIndex = 0;
	}

	if (navIndex > 0) {
		$nav.find('a').removeClass('active');
		$nav.find('a:eq(' + navIndex + ')').addClass('active');
	}

	$nav.find('a').click(function(event)
	{
		sessionStorage.setItem(navKey, $(this).parent().index() - 1); // -1 because of icon li
	});

	$('#header nav .icon').click(function(event)
	{
		$nav.toggleClass('max');
	})
});