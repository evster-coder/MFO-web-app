document.addEventListener("DOMContentLoaded", function(){
    var links_list = document.querySelectorAll('#navContent a');
    var activeElem;

    //проходим по каждой ссылке и находим активную, запоминаем ее
    //для дальнейшей обработки
    links_list.forEach( function(a_element) {
        let location = window.location.protocol + '//' + window.location.host + window.location.pathname;
        let link = a_element.href;
        if(location == link)
        {
            activeElem = a_element;
            a_element.classList.add('active');
        }

        a_element.addEventListener("click", clickSubmenu);
    });

    //кликаем по родителю активной ссылки (если есть has-sub)
    if(activeElem != null)
    {
    	parentNode = activeElem.parentNode.parentNode;

    	if(parentNode 
			&& parentNode.parentNode 
			&& parentNode.parentNode.classList.contains('has-sub'))
    	{
    		var node = parentNode.parentNode.querySelector('a');
    		node.click();
    	}

    }
});

function clickSubmenu(e)
{
	//показываем либо скрываем элемент подменю
	var openedNode = document.querySelector('.is-open');
	if(openedNode != null)
		openedNode.classList.remove('is-open');

	e.target.closest('li').classList.add('is-open');

	var checkElement = e.target.nextElementSibling;
	if(!checkElement)
		return;
	if((checkElement.nodeName =='UL') && ($(checkElement).is(':visible'))) {
		e.target.closest('li').classList.remove('is-open');
		$(checkElement).slideUp('normal');
	}
	if((checkElement.nodeName == 'UL') && (!$(checkElement).is(':visible'))) {
		$('.navbar-nav ul ul:visible').slideUp('normal');
		$(checkElement).slideDown('normal');
	}

}
