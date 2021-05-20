
@extends('layouts.user')

@section('title')
	@if($clientform->exists)
		Редактирование заявки на выдачу займа
	@else
		Создание заявки на выдачу займа
	@endif
@endsection

@push('assets')
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
    		<form action="{{route('clientform.update')}}" method="POST">
    		@method('PUT')
    	@else
    		<form action="{{route('clientform.store')}}" method="POST">
    		@method('POST')
    	@endif

    	@csrf

		<div class="btn-group">
			<button type="submit" class="btn btn-success">
				Создать
			</button>
			<button type="button" class="btn btn-default" onclick="javascript:history.back()">Назад</button>
		</div>


	    	<div class="block-section">
	    		<h4>Клиент</h4>
	          	<div class="form-group edit-fields">
			      	<label for="orgunit_id">Клиент</label>
		      		<select2 required class="form-group" data-width="100%" 
		      				:options="{{$clients}}" name="client_id" id="client_id"
				      				value="{{old('client_id', $clientform->client_id)}}"	>
		      				>
		      			<option value="" disabled> Введите Клиента</option>
		      		</select2>
	          	</div>
	      	</div>

	    	<div class="block-section">
	    		<h4>Основное</h4>
	    		<div class="row">
		  			<div class="col">
			      	<label>Ф.И.О.</label>
		    		<input disabled type="text" class="form-control" placeholder="ФИО">
	      			</div>

	      			<div class="col">
			      	<label>Дата рождения</label>
		    		<input disabled type="date" class="form-control" placeholder="Дата рождения">
		    		</div>
	          	</div>
	          	<div class="row">
	          		<div class="col">
			      	<label>Мобильный телефон</label>
		    		<input type="text" class="form-control"
		    				name="mobilePhone" id="mobilePhone" placeholder="Введите мобильный телефон">
	          		</div>
	          		<div class="col">
			      	<label>Домашний телефон</label>
		    		<input type="text" class="form-control" 
		    				name="homePhone" id="homePhone" placeholder="Введите домашний телефон">
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
			    			placeholder="Серия">
			  		</div>
			  		<div class="col">
				      	<label>Номер</label>
			    		<input required type="text" class="form-control"
			    			name="passportNumber" id="passportNumber" 
			    			placeholder="Номер">
			  		</div>
			  		<div class="col">
				      	<label>Дата выдачи</label>
			    		<input required type="date" class="form-control"
			    			name="passportDateIssue" id="passportDateIssue" 
			    			placeholder="Дата выдачи">
			  		</div>
				</div>

				<div class="form-group edit-fields">
		            <label>Кем выдан</label>
		            <textarea required class="form-control" 
		            	placeholder="Введите кем выдан"
		            	id="passportIssuedBy" name="passportIssuedBy" ></textarea>
	          	</div>

				<div class="form-group edit-fields">
		            <label >Код подразделения</label>
		            <input type="text" class="form-control" 
		            			name="passportDepartamentCode" id="passportDepartamentCode"	placeholder="Введите код подразделения">
	          	</div>

				<div class="form-group edit-fields">
		            <label>Место рождения</label>
		            <textarea class="form-control" 
		            	id="passportBirthplace" name="passportBirthplace" placeholder="Введите место рождения" ></textarea>
	          	</div>

	    		<div class="row">
			  		<div class="col">
				      	<label>СНИЛС</label>
			    		<input required type="text" class="form-control"
			    			name="snils" id="snils" placeholder="Введите СНИЛС">
			  		</div>
			  		<div class="col">
				      	<label>Пенсионное удостоверение №</label>
			    		<input type="text" class="form-control" 
			    			name="pensionerId" id="pensionerId" placeholder="Введите пенсионное удостоверение">
			  		</div>
				</div>
	      	</div>

	      	<div class="block-section">
	      		<h4>Адрес проживания</h4>
				<div class="form-group edit-fields">
		            <label>По паспорту</label>
		            <textarea required class="form-control" 
		            	name="passportResidenceAddress" id="passportResidenceAddress" placeholder="Введите по паспорту" ></textarea>
	          	</div>

				<div class="form-group edit-fields">
		            <label>Фактически</label>
		            <textarea required class="form-control" 
		            		id="actualResidenceAddress" name="actualResidenceAddress" placeholder="Введите фактически" ></textarea>
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
			        	<select required class="form-select"
			        		name="numberOfDependents" id="numberOfDependents">
			        		<option value="0">0</option>
			        		<option value="1">1</option>
			        		<option value="2">2</option>
			        		<option value="3">3</option>
			        	</select>
	    			</div>
	          	</div>
	      	</div>

	      	<div class="block-section">
	      		<h4>Информация о работе</h4>
				<div class="form-group edit-fields">
		            <label >Наименование организации</label>
		            <input required type="text" class="form-control" 
		            	name="workPlaceName" id="workPlaceName"
	    				placeholder="Введите наименование организации">
	          	</div>
				<div class="form-group edit-fields">
		            <label >Адрес работы</label>
		            <input type="text" class="form-control" 
		            	name="workPlaceAddress" id="workPlaceAddress"
	    				placeholder="Введите адрес работы">
	          	</div>
				<div class="row">
					<div class="col">
		            <label >Должность</label>
		            <input type="text" class="form-control" 
		            	name="workPlacePosition" id="workPlacePosition"
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
	    				placeholder="Введите рабочий телефон">
	          	</div>

				<div class="row">
					<div class="col">
						<label >Основной доход</label>
						<div class="input-group">
				            <input required type="number" step="0.01" 
				            	class="form-control" 
	            				placeholder="Введите основной доход"
	            				name="constainIncome" id="constainIncome">
			  				<span class="input-group-text">руб.</span>
						</div>
			        </div>
	    			<div class="col">
		            <label >Дополнительный доход</label>
	    				<div class="input-group">	            
							<input required 
								type="number" step="0.01" class="form-control" 
								name="additionalIncome" id="additionalIncome"
	            				placeholder="Введите доп. доход">
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
							<input required type="number" step="0.01" 
								class="form-control" 
								name="loanCost" id="loanCost"
	            				placeholder="Сумма займа">
				  			<span class="input-group-text">руб.</span>
				  		</div>
	    			</div>
	    			<div class="col">
				    	<autocomplete-component
				    		type="number"
				    		rusname="Срок займа"
				    		name="daysAmount"
				    		path="{{route('loanterm.axiosList')}}"
				    		id="loanTerm"
				    		group-text="дней"
				    	></autocomplete-component>	    			
				    </div>

	    			<div class="col">
				    	<autocomplete-component
				    		type="number"
				    		rusname="Процентная ставка"
				    		name="percentValue"
				    		path="{{route('interestrate.axiosList')}}"
				    		id="interestRate"
				    		group-text="%"
				    		step="0.001"
				    	></autocomplete-component>	    			

	    			</div>

	          	</div>
				<div class="row">
	    			<div class="col">
			            <label >Дата оформления</label>
			            <input required type="date" class="form-control" 
			            	name="loanDate" id="loanDate"
	        				placeholder="Введите дату оформления">
	    			</div>
					<div class="col">
			            <label >Дата погашения</label>
			            <input disabled type="text" class="form-control" 
			            	id="loanMaturityDate" name="loanMaturityDate"
	        				placeholder="Дата погашения погашения">
	    			</div>
	          	</div>

				<div class="row">
	    			<div class="col">
			            <label >Имеются ли действующие кредиты, займы</label>
			        	<select required class="form-select"
			        		name="hasCredits" id="hasCredits">
			        		<option value="0" selected>нет</option>
			        		<option value="1">да</option>
			        	</select>
	    			</div>
					<div class="col">
			            <label >Ежемесячный платеж</label>
	    				<div class="input-group">	            
							<input disabled type="number" step="0.01" 
								class="form-control"
								name="monthlyPayment" id="monthlyPayment" 
	            				placeholder="Ежемесячный платеж">
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
						<input type="radio" name="isBankrupt" id=""
							class="form-check-input"  value="1">
						<label class="form-check-label" for="inlineRadio1">Да</label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" name="isBankrupt" id="" 
							class="form-check-input" value="0" checked>
						<label class="form-check-label" for="inlineRadio2">Нет</label>
					</div>
				</div>

			</div>

	      	<div class="block-section">
	      		<h4>Комментарий кассира</h4>
				<textarea class="form-control" placeholder="Введите комментарий"
					id="cashierComment" name="cashierComment"></textarea>

			</div>
		</form>
  	</div>

@endsection