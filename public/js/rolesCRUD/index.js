document.addEventListener("DOMContentLoaded", roleManage);

function roleManage()
{
	var btnDelete = document.querySelector(".erase-role");
	if ( btnDelete != null )
	{
		btnDelete.addEventListener("click", activateForm);
	}

}

function activateForm(e)
{
	e.preventDefault();

	var btn = document.querySelector("#deleteForm button");

	if(btn != null)
	{
		btn.click();
	}
}