document.addEventListener("DOMContentLoaded", ready);

function ready()
{


	//filtering and search
	const directorTbody = document.querySelector('#directorTable tbody');
	const securityTbody = document.querySelector('#securityTable tbody');
	if(document.getElementById('directorTable'))
	{
		if(document.getElementById('searchFrom'))
			document.getElementById('searchFrom').addEventListener('keyup', (e) =>{
				if(e.key==="Enter")
					updateDirectorTable();
			});
		if(document.getElementById('searchTo'))
			document.getElementById('searchTo').addEventListener('keyup', (e) =>{
				if(e.key==="Enter")
					updateDirectorTable();
			});

		//exports
		if(document.getElementById('exportExcelDirector'))
		{
			document.getElementById('exportExcelDirector').addEventListener('click', (e) =>{
				e.preventDefault();
				
				var dateFrom = document.getElementById('searchFrom').value;
				var dateTo = document.getElementById('searchTo').value;
				const url = e.target.dataset.export;

		    	window.location.href = url +           
	     			"/?dateFrom=" + dateFrom
		     		+ "&dateTo=" + dateTo;
	     	});
		}

	}
	else if(document.getElementById('securityTable'))
	{
		if(document.getElementById('searchFrom'))
			document.getElementById('searchFrom').addEventListener('keyup', (e) =>{
				if(e.key==="Enter")
					updateSecurityTable();
			});
		if(document.getElementById('searchTo'))
			document.getElementById('searchTo').addEventListener('keyup', (e) =>{
				if(e.key==="Enter")
					updateSecurityTable();
			});


		//exports
		if(document.getElementById('exportExcelSec'))
		{
			document.getElementById('exportExcelSec').addEventListener('click', (e) =>{
				e.preventDefault();
					
				var dateFrom = document.getElementById('searchFrom').value;
				var dateTo = document.getElementById('searchTo').value;

				const url = e.target.dataset.export;

		    	window.location.href = url +           
	     			"/?dateFrom=" + dateFrom
		     		+ "&dateTo=" + dateTo;
	     	});
		}
	}

	function updateSecurityTable()
	{
		var dateFrom = document.getElementById('searchFrom').value;
		var dateTo = document.getElementById('searchTo').value;

		const url = document.getElementById('securityTable').dataset.url;

    	$.ajax({
	     url: url + 
	     "/?dateFrom=" + dateFrom
	     + "&dateTo=" + dateTo,
	     success:function(data)
	       {
	        securityTbody.innerHTML = "";
	        securityTbody.innerHTML = data;
	       }
	    });
	}

	function updateDirectorTable()
	{
		var dateFrom = document.getElementById('searchFrom').value;
		var dateTo = document.getElementById('searchTo').value;

		const url = document.getElementById('directorTable').dataset.url;

    	$.ajax({
	     url: url + 
	     "/?dateFrom=" + dateFrom
	     + "&dateTo=" + dateTo,
	     success:function(data)
	       {
	        directorTbody.innerHTML = "";
	        directorTbody.innerHTML = data;
	       }
	    });
	}
}