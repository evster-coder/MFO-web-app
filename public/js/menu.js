$(document).ready(function () {
			$('nav .container .navbar-nav > ul > li ul').each(function(index, e){
				var count = $(e).find('li').length;
			});
			$('nav .container .navbar-nav ul ul li:odd').addClass('odd');
			$('nav .container .navbar-nav ul ul li:even').addClass('even');
			$('nav .container .navbar-nav > ul > li > a').click(function() {
				$('nav .container .navbar-nav li').removeClass('active');
				$(this).closest('li').addClass('active');	
				var checkElement = $(this).next();
				if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
					$(this).closest('li').removeClass('active');
					checkElement.slideUp('normal');
				}
				if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
					$('nav .container .navbar-nav ul ul:visible').slideUp('normal');
					checkElement.slideDown('normal');
				}
				if($(this).closest('li').find('ul').children().length == 0) {
					return true;
				} else {
					return false;
				}
			});
});
