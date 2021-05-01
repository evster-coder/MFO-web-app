document.addEventListener("DOMContentLoaded", ready);

function ready() {
	var orgUnits = document.getElementById("orgUnitsList")

	var items = document.getElementsByClassName("expandIcon");

	var i = items.length;
	while(i--)
	{
		items[i].textContent = "-";
		items[i].addEventListener("click", orgUnitItemClick);
		items[i].click();
	}

	if (orgUnits == null)
		return;
	var radioBtns = orgUnits.querySelectorAll('.orgUnitItem label span input');
	var checked;
	for(var i = 0; i < radioBtns.length; i++)
	{
		if(radioBtns[i].checked)
			checked = radioBtns[i];
	}

	var parentNode = checked.closest('div');
	var spanClick;
	while(parentNode != null)
	{
		parentNode = parentNode.parentNode.closest('div');
		if(parentNode && parentNode.classList.contains('modal-body') == false)
		{
			spanClick = parentNode.querySelector('.expandIcon');
			if(spanClick)
				spanClick.click();
			else break;
		}
		else break;
	}
}

function orgUnitItemClick(e)
{
	var liItem = e.target.parentNode.querySelector('li');
	var childUl = e.target.parentNode.querySelector('li ul');
	if(childUl == null)
		return;

	if(liItem.classList.contains('collapsed'))
	{
		liItem.classList.remove('collapsed');
		liItem.classList.add('expanded');

		e.target.textContent =  '-';

		childUl.style.display = "block";
	}

	else if(liItem.classList.contains('expanded'))
	{
		liItem.classList.remove('expanded');
		liItem.classList.add('collapsed');

		e.target.textContent =  '+';

		childUl.style.display = "none";	
	}


}