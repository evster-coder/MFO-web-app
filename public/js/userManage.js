document.addEventListener("DOMContentLoaded", userManage);

function userManage()
{
	var togglePassword = document.querySelector(".toggle-password");
	if ( togglePassword != null )
	{
		togglePassword.addEventListener("click", clickToggle);
	}

	var randomPassword = document.getElementById("random-password");
	if ( randomPassword != null )
	{
		randomPassword.addEventListener("click", setRandomPassword);
	}



}

function clickToggle(e)
{
	e.target.classList.toggle("fa-eye");
	e.target.classList.toggle("fa-eye-slash");
  	var input = document.querySelector((e.target.getAttribute("toggle")));
  	if(input == null)
  		return;

  	if(input.getAttribute('type') == "password")
	  	input.setAttribute('type', 'text');
  	else
	  	input.setAttribute('type', 'password');
}

function setRandomPassword(e)
{
	e.preventDefault();
	var password = Math.random().toString(36).substring(2, 6) 
					+ Math.random().toString(36).substring(2, 6);
	var input = document.getElementById('password');

	if(input != null)
		input.value = password;
}