function myFunction() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

document.addEventListener("DOMContentLoaded", userManage);

function userManage()
{
	document.querySelector(".toggle-password")
			.addEventListener("click", function(e){
				e.target.classList.toggle("fa-eye");
				e.target.classList.toggle("fa-eye-slash");
				  var input = document.querySelector((e.target.getAttribute("toggle")));
				  if(input == null)
				  	return;

				  if(input.getAttribute('type') == "password")
				  	input.setAttribute('type', 'text');
				  else
				  	input.setAttribute('type', 'password');
			});

}