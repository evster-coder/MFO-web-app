
@extends('layouts.user')

@section('title')
	@if($clientform->exists)
		Редактирование заявки на выдачу займа
	@else
		Создание заявки на выдачу займа
	@endif
@endsection

@push('assets')
    <script src="{{ asset('js/clientformsCRUD/create.js') }}" defer></script>
@endpush


@section('content')

	@if($clientform->exists)
		<h1>Редактирование заявки на выдачу займа</h1>
	@else
		<h1>Создание заявки на выдачу займа</h1>
	@endif	
	<div class="content-block">


		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

    	@if($clientform->exists)
    		<form action="{{route('clientform.update', ['id' => $clientform->id])}}" method="POST">
    		@method('PUT')
    	@else
    		<form action="{{route('clientform.store')}}" method="POST">
    		@method('POST')
    	@endif

    	@csrf

		<div class="btn-group">
			<button type="submit" class="btn btn-success">
				@if($clientform->exists)
				Обновить
				@else
				Создать
				@endif
			</button>
			<button type="button" class="btn btn-default" onclick="javascript:history.back()">Назад</button>
		</div>


	    	<div class="block-section">
	    		<h4>Клиент</h4>
	          	<div class="form-group edit-fields">
			      	<label for="orgunit_id">Клиент</label>
		      		<select2 required
		      				class="form-group" data-width="100%" 
		      				:options="{{$clients}}" name="client_id" id="client_id"
				      		value="{{old('client_id', $clientform->client_id)}}">
		      		</select2>
	      		    <div class="wrapper" id="wrp" style="display: none;">
				    <a href="#" id="addClient" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#crud-modal">+ Новый клиент</a>
				    </div>
	          	</div>
      		</div>

	    	<div class="block-section">
	    		<h4>Основное</h4>
	    		<div class="row">
		  			<div class="col">
			      	<label>Ф.И.О.</label>
		    		<input disabled type="text" id="clientFIO"class="form-control" placeholder="ФИО">
	      			</div>

	      			<div class="col">
			      	<label>Дата рождения</label>
		    		<input disabled type="date" id="clientBirthDate" 
		    		class="form-control" placeholder="Дата рождения">
		    		</div>
	          	</div>
	          	<div class="row">
	          		<div class="col">
			      	<label>Мобильный телефон</label>
		    		<input type="text" class="form-control"
		    				name="mobilePhone" id="mobilePhone" 
		    				placeholder="Введите мобильный телефон" 
		    				value="{{old('mobilePhone', $clientform->mobilePhone)}}">
	          		</div>
	          		<div class="col">
			      	<label>Домашний телефон</label>
		    		<input type="text" class="form-control" 
		    				name="homePhone" id="homePhone" 
		    				placeholder="Введите домашний телефон"
		    				value="{{old('homePhone', $clientform->homePhone)}}">
	          		</div>
	          	</div>
	      	</div>

	    	<div class="block-section">
	    		<h4>Паспортные данные</h4>
	    		<div class="row">
			  		<div class="col">
				      	<label>Серия</label>
			    		<input required type="text" class="form-control"
			    			name="passportSeries" id="passportSeries" 
			    			placeholder="Серия"
			    			value="{{old('passportSeries', $clientform->passportSeries)}}">
			  		</div>
			  		<div class="col">
				      	<label>Номер</label>
			    		<input required type="text" class="form-control"
			    			name="passportNumber" id="passportNumber" 
			    			value="{{old('passportNumber', $clientform->passportNumber)}}"
			    			placeholder="Номер">
			  		</div>
			  		<div class="col">
				      	<label>Дата выдачи</label>
			    		<input required type="date" class="form-control"
			    			name="passportDateIssue" id="passportDateIssue" 
			    			placeholder="Дата выдачи"
			    			value="{{old('passportDateIssue', $clientform->passportDateIssue)}}">
			  		</div>
				</div>

				<div class="form-group edit-fields">
		            <label>Кем выдан</label>
		            <textarea required class="form-control" 
		            	placeholder="Введите кем выдан"
		            	id="passportIssuedBy" name="passportIssuedBy">{{old('passportIssuedBy', $clientform->passportIssuedBy)}}</textarea>
	          	</div>

				<div class="form-group edit-fields">
		            <label >Код подразделения</label>
		            <input type="text" class="form-control" 
		            			name="passportDepartamentCode" id="passportDepartamentCode"	
		            			value="{{old('passportDepartamentCode', $clientform->passportDepartamentCode)}}"
		            			placeholder="Введите код подразделения">
	          	</div>

				<div class="form-group edit-fields">
		            <label>Место рождения</label>
		            <textarea class="form-control" 
		            	id="passportBirthplace" name="passportBirthplace" 
		            	placeholder="Введите место рождения" >{{old('passportBirthplace', $clientform->passportBirthplace)}}</textarea>
	          	</div>

	    		<div class="row">
			  		<div class="col">
				      	<label>СНИЛС</label>
			    		<input required type="text" class="form-control"
			    			name="snils" id="snils" placeholder="Введите СНИЛС"
			    			value="{{old('snils', $clientform->snils)}}">
			  		</div>
			  		<div class="col">
				      	<label>Пенсионное удостоверение №</label>
			    		<input type="text" class="form-control" 
			    			name="pensionerId" id="pensionerId" 
			    			value="{{old('pensionerId', $clientform->pensionerId)}}"
			    			placeholder="Введите пенсионное удостоверение">
			  		</div>
				</div>
	      	</div>

	      	<div class="block-section">
	      		<h4>Адрес проживания</h4>
				<div class="form-group edit-fields">
		            <label>По паспорту</label>
		            <textarea required class="form-control" 
		            	name="passportResidenceAddress" id="passportResidenceAddress"
		            	placeholder="Введите по паспорту" >{{old('passportResidenceAddress', $clientform->passportResidenceAddress)}}</textarea>
	          	</div>

				<div class="form-group edit-fields">
		            <label>Фактически</label>
		            <textarea required class="form-control" 
		            		id="actualResidenceAddress" name="actualResidenceAddress" 
		            		placeholder="Введите фактически" >{{old('actualResidenceAddress', $clientform->actualResidenceAddress)}}</textarea>
	          	</div>
	      	</div>

	     	<div class="block-section">
	      		<h4>Информация о семейном положении</h4>
				<div class="row">
					<div class="col">
	    				<label>Семейное положение</label>
			        	<select required class="form-select"
			        		name="maritalstatus_id" id="maritalstatus_id">
			        	@foreach($maritalstatuses as $maritalstatus)
			        		<option @if ( $maritalstatus->id == $clientform->maritalstatus_id ) selected @endif
		        			 id = "maritalstatus_id" name="maritalstatus_id" value=" {{ $maritalstatus->id }} ">{{$maritalstatus->name}}
			        		</option>
			        	@endforeach
			        	</select>
	    			</div>
	    			<div class="col">
	    				<label>Количество детей</label>
						<input type="number"
								class="form-control"
								name="numberOfDependents" id="numberOfDependents" 
	            				placeholder="Количество иждевенцов"
		            			value="{{old('numberOfDependents', $clientform->numberOfDependents)}}"
	            				min="0">
	    			</div>
	          	</div>
	      	</div>

	      	<div class="block-section">
	      		<h4>Информация о работе</h4>
				<div class="form-group edit-fields">
		            <label >Наименование организации</label>
		            <input required type="text" class="form-control" 
		            	name="workPlaceName" id="workPlaceName"
	    				placeholder="Введите наименование организации"
	    				value="{{old('workPlaceName', $clientform->workPlaceName)}}">
	          	</div>
				<div class="form-group edit-fields">
		            <label >Адрес работы</label>
		            <textarea required class="form-control" 
		            	name="workPlaceAddress" id="workPlaceAddress"
	    				placeholder="Введите адрес работы">{{old('workPlaceAddress', $clientform->workPlaceAddress)}}</textarea>
	          	</div>
				<div class="row">
					<div class="col">
		            <label >Должность</label>
		            <input type="text" class="form-control" 
		            	name="workPlacePosition" id="workPlacePosition"
						value="{{old('workPlacePosition', $clientform->workPlacePosition)}}"
	    				placeholder="Введите должность">
	    			</div>
	    			<div class="col">
	    				<label>Стаж на месте работы</label>
			        	<select required class="form-select"
			        		name="seniority_id" id="seniority_id">
			        	@foreach($seniorities as $seniority)
			        		<option @if ( $seniority->id == $clientform->seniority_id ) selected @endif
		        			 id = "seniority_id" name="seniority_id" value="{{ $seniority->id }}">{{$seniority->name}}</option>
		        		@endforeach
			        	</select>
	    			</div>
	          	</div>

				<div class="form-group edit-fields">
		            <label >Рабочий телефон</label>
		            <input type="text" class="form-control" 
		            	name="workPlacePhone" id="workPlacePhone"
	    				placeholder="Введите рабочий телефон"
	    				value="{{old('workPlacePhone', $clientform->workPlacePhone)}}">
	          	</div>

				<div class="row">
					<div class="col">
						<label >Основной доход</label>
						<div class="input-group">
				            <input required type="number" step="0.01" min="0"
				            	class="form-control" 
	            				placeholder="Введите основной доход"
	            				name="constainIncome" id="constainIncome"
	            				value="{{old('constainIncome', $clientform->constainIncome)}}">
			  				<span class="input-group-text">руб.</span>
						</div>
			        </div>
	    			<div class="col">
		            <label >Дополнительный доход</label>
	    				<div class="input-group">	            
							<input required 
								type="number" step="0.01" min="0" class="form-control" 
								name="additionalIncome" id="additionalIncome"
	            				placeholder="Введите доп. доход"
	            				value="{{old('additionalIncome', $clientform->additionalIncome)}}">
				  			<span class="input-group-text">руб.</span>
				  		</div>

			        </div>
	          	</div>

	      	</div>

	     	<div class="block-section">
	      		<h4>Информация о займе</h4>
				<div class="row">
					<div class="col">
	    				<label>Сумма займа</label>
	    				<div class="input-group">	            
							<input required type="number" step="0.01" min="0"
								class="form-control" 
								name="loanCost" id="loanCost"
	            				placeholder="Сумма займа"
	            				value="{{old('loanCost', $clientform->loanCost)}}">
				  			<span class="input-group-text">руб.</span>
				  		</div>
	    			</div>
	    			<div class="col">
				    	<autocomplete-component
				    		type="number"
				    		required
				    		rusname="Срок займа"
				    		name="daysAmount"
				    		path="{{route('loanterm.axiosList')}}"
				    		id="loanTerm"
				    		group-text="дней"
				    		given-value="{{old('loanTerm', $clientform->loanTerm)}}"
				    		selector="loanTerm"
				    	></autocomplete-component>	    			
				    </div>

	    			<div class="col">
				    	<autocomplete-component
				    		type="number"
				    		required
				    		rusname="Процентная ставка"
				    		name="percentValue"
				    		path="{{route('interestrate.axiosList')}}"
				    		id="interestRate"
				    		group-text="%"
				    		step="0.001"
				    		given-value="{{old('interestRate', $clientform->interestRate)}}"
				    	></autocomplete-component>	    			

	    			</div>

	          	</div>
				<div class="row">
	    			<div class="col">
			            <label >Дата оформления</label>
			            <input required type="date" class="form-control" 
			            	name="loanDate" id="loanDate"
	        				placeholder="Введите дату оформления"
	        				value="{{old('loanDate', $clientform->loanDate)}}">
	    			</div>
					<div class="col">
			            <label >Дата погашения</label>
			            <input disabled type="date" class="form-control" 
			            	id="loanMaturityDate" name="loanMaturityDate"
	        				placeholder="Дата погашения">
	    			</div>
	          	</div>

				<div class="row">
	    			<div class="col">
			            <label >Имеются ли действующие кредиты, займы</label>
			        	<select required class="form-select"
			        		name="hasCredits" id="hasCredits">
			        		<option value="0" 
			        		@if(!$clientform->hasCredits) selected @endif>
			        			нет
			        		</option>
			        		<option value="1" 
			        		@if($clientform->hasCredits) selected @endif>
			        			да
			        		</option>
			        	</select>
	    			</div>
					<div class="col">
			            <label >Ежемесячный платеж</label>
	    				<div class="input-group">	            
							<input type="number" step="0.01" min="0"
								@if(!$clientform->hasCredits)
								readonly
								@endif
								class="form-control"
								name="monthlyPayment" id="monthlyPayment" 
	            				placeholder="Ежемесячный платеж"
								value="{{old('monthlyPayment', $clientform->monthlyPayment)}}"
	            				value="0">
				  			<span class="input-group-text">руб.</span>
				  		</div>    			
				  	</div>
	          	</div>

	      	</div>

	      	<div class="block-section">
	      		<h4>Сведения о банкротстве</h4>
	      		<p align="center">Наличие производства по делу о несостоятельности (банкротстве) в течение 5 (пяти) лет на дату подачи заявки на получение микрозайма</p>
	      		<div class="container">
					<div class="form-check form-check-inline">
						<input type="radio" name="isBankrupt"
							class="form-check-input"  value="1" 
							@if($clientform->isBankrupt) checked @endif>
						<label class="form-check-label" for="inlineRadio1">Да</label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" name="isBankrupt" 
							class="form-check-input" value="0"
							@if(!$clientform->isBankrupt) checked @endif>
						<label class="form-check-label" for="inlineRadio2">Нет</label>
					</div>
				</div>

			</div>

	      	<div class="block-section">
	      		<h4>Комментарий кассира</h4>
				<textarea class="form-control" placeholder="Введите комментарий"
					id="cashierComment" name="cashierComment">{{old('cashierComment', $clientform->cashierComment)}}</textarea>

			</div>
		</form>

    <div class="modal fade" id="crud-modal" aria-hidden="true" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Новый клиент</h4>
				</div>
			  	<div class="modal-body">
				    <form name="addClientForm" id="addClientForm" action="{{route('client.store')}}" method="POST">
				      @csrf
				      <input id="isJSON" type="hidden" value="1">
				        <div class="form-group edit-fields">
				          <label for="surname">Фамилия</label>
				          <input type="text" name="surname" id="surname" class="form-control" placeholder="Фамилия" oninput="validate()" >
				        </div>
				        <div class="form-group edit-fields">
				            <label for="name" >Имя</label>
				            <input type="text" name="name" id="name" class="form-control" placeholder="Имя" oninput="validate()">
				        </div>
				        <div class="form-group edit-fields">
				            <label for="patronymic" >Отчество</label>
				            <input type="text" name="patronymic" id="patronymic" class="form-control" placeholder="Отчество">
				        </div>
				        <div class="form-group edit-fields">
				            <label for="birthDate" >Дата рождения</label>
				            <input type="date" name="birthDate" id="birthDate" class="form-control" placeholder="Дата рождения" oninput="validate()">
				        </div>
				        <div class="btn-group block-padding">
				          <button type="submit" id="btn-save" name="btnsave" class="btn btn-primary" disabled>Подтвердить</button>
				        </div>
				    </form>
			  	</div>
			</div>
		</div>
	</div>
  	</div>

@endsection