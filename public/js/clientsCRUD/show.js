document.addEventListener("DOMContentLoaded", ready);

function ready()
{
	const selectElement = document.getElementById('formNumber');
	var resultDiv = document.getElementById('showInfo');

	if(selectElement)
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



	const clientId = document.getElementById('clientId').value;
	var clientformsTable = document.querySelector('#tableClientforms tbody');
	var loansTable = document.querySelector('#tableLoans tbody');

	if(document.getElementById('searchFrom'))
		document.getElementById('searchFrom').addEventListener('keyup', (e) =>{
			if(e.key==="Enter")
			{
				updateClientformsTable();
			}
		});

	if(document.getElementById('searchTo'))
		document.getElementById('searchTo').addEventListener('keyup', (e) =>{
			if(e.key==="Enter")
			{
				updateClientformsTable();
			}
		});

	if(document.getElementById('searchLoanNumber'))
		document.getElementById('searchLoanNumber').addEventListener('keyup', (e) =>{
			if(e.key==="Enter")
			{
				updateLoansTable();
			}
		});

	if(document.getElementById('searchLoanConslusionDate'))
		document.getElementById('searchLoanConslusionDate').addEventListener('keyup', (e) =>{
			if(e.key==="Enter")
			{
				updateLoansTable();
			}
		});

	function updateClientformsTable()
	{
		var dateFrom = document.getElementById('searchFrom').value;
		var dateTo = document.getElementById('searchTo').value;

    	$.ajax({
	     url:"/client-clientforms/" + clientId + 
	     "/?dateFrom=" + dateFrom
	     + "&dateTo=" + dateTo,
	     success:function(data)
	       {
	        clientformsTable.innerHTML = "";
	        clientformsTable.innerHTML = data;
	       }
	    });
	}

	function updateLoansTable()
	{
		var number = document.getElementById('searchLoanNumber').value;
		var date = document.getElementById('searchLoanConslusionDate').value;

    	$.ajax({
	     url:"/client-loans/" + clientId + 
	     "/?loanNumber=" + number
	     + "&loanConclusionDate=" + date,
	     success:function(data)
	       {
	        loansTable.innerHTML = "";
	        loansTable.innerHTML = data;
	       }
	    });

	}


}
