

document.addEventListener("DOMContentLoaded", function(){
	var selectClient = document.getElementById('client_id');
	var selectHasCredits = document.getElementById('hasCredits');
	var textMonthlyPayment = document.getElementById('monthlyPayment');
	var loanDate = document.getElementById('loanDate');
	var loanMaturityDate = document.getElementById('loanMaturityDate');
	var loanTerm = document.getElementById('loanTerm');

	changeDate();

	if(selectClient)
	{
		var isShow = false;
		$('#client_id').on("select2:open", function () {
		      if (isShow == false) {
		        var htmlAddLink = document.getElementById('wrp').innerHTML;
		        document.querySelector('.select2-results')
		        		.insertAdjacentHTML('beforeend', "<div class='select2-results__option'>" + 
		      htmlAddLink + "</div>");
		        isShow = true;
		      }
		});
		$('#client_id').on("change.select2", function() {
			var selectedItem = $('#client_id').select2('data')[0];
			var patronymic = selectedItem.patronymic ? selectedItem.patronymic : "";
			document.getElementById('clientFIO').value = selectedItem.surname + " "
			 + selectedItem.name + " " + patronymic;

			document.getElementById('clientBirthDate').value = selectedItem.birthDate;
		})
	}

	//установить текущую дату
	if(loanDate != null && loanDate.valueAsDate == null)
		loanDate.valueAsDate = new Date();

	//установить readonly для ввода ежемесячного платежа при отсутствии платежей
	if(selectHasCredits != null)
		selectHasCredits.addEventListener("change", function(e)
			{
				if(textMonthlyPayment != null)
				{
					if(e.target.value == 0)
					{
						textMonthlyPayment.setAttribute('readonly', true);
						textMonthlyPayment.value = "0";
					}
					else if(e.target.value == 1)
					{
						textMonthlyPayment.removeAttribute('readonly');
						textMonthlyPayment.value = "0";
					}

				}
			});


	if(loanTerm != null)
	{
		loanTerm.addEventListener("change", changeDate);
		loanDate.addEventListener("change", changeDate);
	}
})

function validate()
{
	if(document.addClientForm.surname.value !='' 
		&& document.addClientForm.name.value != ''
		&& document.addClientForm.birthDate.valueAsDate != null)
	{
	  document.addClientForm.btnsave.disabled=false
	}
	else
	  document.addClientForm.btnsave.disabled=true
}

function changeDate(e)
{
	if(loanTerm != null 
		&& loanTerm.value != null
		&& loanDate != null
		&& loanDate.valueAsDate != null
		&& loanMaturityDate != null)
	{
		var date = loanDate.valueAsDate;
		date.setDate(parseInt(date.getDate()) 
			+ parseInt(loanTerm.value));
		loanMaturityDate.valueAsDate = date;

	}
}
