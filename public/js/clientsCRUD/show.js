document.addEventListener("DOMContentLoaded", ready);

function ready()
{
	const selectElement = document.getElementById('formNumber');
	var resultDiv = document.getElementById('showInfo');

	selectElement.addEventListener('change', (e) => {
    	$.ajax({
	     url:"/clientform-data/" +  e.target.value,
	     success:function(data)
	       {
	        resultDiv.innerHTML = "";
	        resultDiv.innerHTML = data;
	       }
	    });
	});
}
