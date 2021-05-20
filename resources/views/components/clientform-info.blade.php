<ul class="nav nav-tabs mt-3" id="blockinfo" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" data-bs-toggle="tab" href="#tabs-1" role="tab">Клиент</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-bs-toggle="tab" href="#tabs-2" role="tab">Паспортные данные</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-bs-toggle="tab" href="#tabs-3" role="tab">Адрес проживания</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-bs-toggle="tab" href="#tabs-4" role="tab">Семейное положение</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-bs-toggle="tab" href="#tabs-5" role="tab">Информация о работе</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-bs-toggle="tab" href="#tabs-6" role="tab">Информация о займе</a>
	</li>
		<li class="nav-item">
		<a class="nav-link" data-bs-toggle="tab" href="#tabs-7" role="tab">Прочее</a>
	</li>
</ul><!-- Tab panes -->
<div class="tab-content">
	<div class="tab-pane active" id="tabs-1" role="tabpanel">
		<h5>Основные сведения: </h5><br>
		<table class="table">
			<tbody>
				<tr> 
					<td>Клиент</td>
					<td><a target="_blank" href="{{route('client.show', ['id' => $clientform->client_id])}}">
						{{$clientform->Client->surname}}  {{$clientform->Client->name}}  {{$clientform->Client->patronymic}}
					</a></td>
				</tr>
				<tr> 
					<td>Дата рождения</td>
					<td>{{date("d-m-Y", strtotime($clientform->Client->birthDate))}}</td>
				</tr>
				<tr> 
					<td>Мобильный телефон</td>
					<td>{{$clientform->mobilePhone}}</td>
				</tr>
				<tr> 
					<td>Домашний телефон</td>
					<td>{{$clientform->homePhone}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="tab-pane" id="tabs-2" role="tabpanel">
		<h5>Паспортные данные: </h5><br>
		<table class="table">
			<tbody>
				<tr> 
					<td>Серия</td>
					<td>{{$clientform->passportSeries}}</td>
				</tr>
				<tr> 
					<td>Номер</td>
					<td>{{$clientform->passportNumber}}</td>
				</tr>
				<tr> 
					<td>Дата выдачи</td>
					<td>{{date("d-m-Y", strtotime($clientform->passportDateIssue))}}</td>
				</tr>
				<tr> 
					<td>Кем выдан</td>
					<td>{{$clientform->passportIssuedBy}}</td>
				</tr>
				<tr> 
					<td>Код подразделения</td>
					<td>{{$clientform->passportDepartamentCode}}</td>
				</tr>
				<tr> 
					<td>Место рождения</td>
					<td>{{$clientform->passportBirthplace}}</td>
				</tr>
				<tr> 
					<td>СНИЛС</td>
					<td>{{$clientform->snils}}</td>
				</tr>
				<tr> 
					<td>Пенсионное удостоверение №</td>
					<td>{{$clientform->pensionerId}}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="tabs-3" role="tabpanel">
		<h5>Адрес проживания: </h5><br>
		<table class="table">
			<tbody>
				<tr> 
					<td>По паспорту</td>
					<td>{{$clientform->passportResidenceAddress}}</td>
				</tr>
				<tr> 
					<td>Фактически</td>
					<td>{{$clientform->actualResidenceAddress}}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="tabs-4" role="tabpanel">
		<h5>Информация о семейном положении: </h5><br>
		<table class="table">
			<tbody>
				<tr> 
					<td>Семейное положение</td>
					<td>{{$clientform->MaritalStatus->name}}</td>
				</tr>
				<tr> 
					<td>Количество детей</td>
					<td>{{$clientform->numberOfDependents}}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="tabs-5" role="tabpanel">
		<h5>Информация о работе: </h5><br>
		<table class="table">
			<tbody>
				<tr> 
					<td>Наименование организации</td>
					<td>{{$clientform->workPlaceName}}</td>
				</tr>
				<tr> 
					<td>Адрес работы</td>
					<td>{{$clientform->workPlaceAddress}}</td>
				</tr>
				<tr> 
					<td>Должность</td>
					<td>{{$clientform->workPlacePosition}}</td>
				</tr>
				<tr> 
					<td>Рабочий телефон</td>
					<td> {{$clientform->workPlacePhone}}</td>
				</tr>

				<tr> 
					<td>Постоянный доход</td>
					<td>{{$clientform->constainIncome}} руб.</td>
				</tr>

				<tr> 
					<td>Дополнительный доход</td>
					<td>{{$clientform->additionalIncome}} руб.</td>
				</tr>
			</tbody>
		</table>	
	</div>

	<div class="tab-pane" id="tabs-6" role="tabpanel">
		<h5>Информация о займе: </h5><br>
		<table class="table">
			<tbody>
				<tr> 
					<td>Сумма займа</td>
					<td>{{$clientform->loanCost}} руб.</td>
				</tr>
				<tr> 
					<td>Процентная ставка</td>
					<td>{{$clientform->interestRate}} %</td>
				</tr>
				<tr> 
					<td>Срок займа</td>
					<td>{{$clientform->loanTerm}} дней</td>
				</tr>
				<tr> 
					<td>Дата оформления</td>
					<td>{{date("d-m-Y", strtotime($clientform->loanDate))}}</td>
				</tr>
				<tr> 
					<td>Предполагаемая дата погашения</td>
					<td>{{date("d-m-Y", strtotime("+". $clientform->loanTerm ."days", strtotime($clientform->loanDate)))}}</td>
				</tr>
				<tr> 
					<td>Имеются ли действующие кредиты, займы: </td>
					<td>@if($clientform->hasCredits)
							да
						@else
							нет
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="tabs-7" role="tabpanel">
		<h5>Прочее: </h5><br>
		<table class="table">
			<tbody>
				<tr> 
					<td>Наличие банкротства</td>
					<td>
						@if($clientform->isBankrupt)
							да
						@else
							нет
						@endif
					</td>
				</tr>
				<tr> 
					<td>Комментарий кассира</td>
					<td>{{$clientform->cashierComment}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
