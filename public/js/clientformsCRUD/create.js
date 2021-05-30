

document.addEventListener("DOMContentLoaded", function(){
	var selectClient = document.getElementById('client_id');
	var selectHasCredits = document.getElementById('hasCredits');
	var textMonthlyPayment = document.getElementById('monthlyPayment');
	var loanDate = document.getElementById('loanDate');
	var loanMaturityDate = document.getElementById('loanMaturityDate');
	var loanTerm = document.getElementById('loanTerm');
	var btnCheckBankrupt = document.getElementById('checkBankrupt');

	//заполняем даты истечения, фио и дату рождения клиента
	changeDate();
	fillFioBirthDate();


	//навешиваем маски ввода
	putMasks();

	if(btnCheckBankrupt != null)
	{
		document.getElementById('saveClientform')
						.setAttribute('disabled', 'true');
		document.getElementById('bankruptTrue')
						.setAttribute('disabled', 'true');
		document.getElementById('bankruptFalse')
						.setAttribute('disabled', 'true');

		btnCheckBankrupt.addEventListener("click", function(e){
			document.getElementById('saveClientform')
						.removeAttribute('disabled');
			document.getElementById('bankruptTrue')
						.removeAttribute('disabled');
			document.getElementById('bankruptFalse')
						.removeAttribute('disabled');
		});
	}


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

		$('#client_id').on("change.select2", fillFioBirthDate);
	}

		function fillFioBirthDate(e){
			//получаем выбранный элемент в селект2
			var selectedItem = $('#client_id').select2('data')[0];
			//если что то было выбрано, то заполняем соответствующие поля
			if(selectedItem)
			{
				var patronymic = selectedItem.patronymic ? selectedItem.patronymic : "";
				document.getElementById('clientFIO').value = selectedItem.surname + " "
				 + selectedItem.name + " " + patronymic;

				document.getElementById('clientBirthDate').value = selectedItem.birthDate;
			}
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

function putMasks()
{
	Inputmask({mask:"+9(999)-999-9999", "clearIncomplete": true})
					.mask(document.getElementById('mobilePhone'));
	Inputmask({mask:"+9(9999)-999999", "clearIncomplete": true})
					.mask(document.getElementById('homePhone'));
	Inputmask({mask:"99 99", "clearIncomplete": true})
					.mask(document.getElementById('passportSeries'));
	Inputmask({mask:"999999", "clearIncomplete": true})
					.mask(document.getElementById('passportNumber'));
	Inputmask({mask:"999-999", "clearIncomplete": true})
					.mask(document.getElementById('passportDepartamentCode'));
	Inputmask({mask:"99999999999", "clearIncomplete": true})
					.mask(document.getElementById('snils'));
	Inputmask({mask:"[9]{1,}", greedy: false})
					.mask(document.getElementById('pensionerId'));
	Inputmask({mask:"+9(999)-999-9999", "clearIncomplete": true})
					.mask(document.getElementById('workPlacePhone'));

}