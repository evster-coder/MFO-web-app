  
@extends('layouts.user')

@section('title')
	Создание платежа
@endsection

@push('assets')
@endpush

@section('content')
	<h1>Создание платежа по договору займа №{{$loan->loanNumber}} от {{date('d-m-Y', strtotime($loan->loanConclusionDate))}}</h1>
	<div class="content-block">
		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

    	<form method="POST" action="{{route('payment.store')}}">
			@method('POST')
    		@csrf
		<div class="btn-group">
			<button type="submit" class="btn btn-success">
				Создать
			</button>
			<a href="{{route('loan.show', $loan->id)}}" type="button" class="btn btn-default">
				К договору займа	
			</a>
		</div>

		<input type="hidden" id="loan_id" name="loan_id" value="{{$loan->id}}">

    	<div class="block-section">
    		<h4>Договор</h4>
    		<div class="row">
	  			<div class="col">
		      	<label>Номер договора</label>
	    		<input type="text" disabled="" class="form-control" placeholder="Введите номер договора" value="{{$loan->loanNumber}}">
      			</div>

      			<div class="col">
		      	<label>Дата </label>
	    		<input type="date" disabled="" class="form-control" placeholder="Выберите дату договора" value="{{$loan->loanConclusionDate}}">
	    		</div>
          	</div>
      	</div>

    	<div class="block-section">
    		<h4>Платеж</h4>
    		<div class="row">

      			<div class="col">
		      	<label>Подразделение</label>
	    		<input type="text" disabled="" class="form-control" placeholder="Введите Подразделение" value="{{$user->OrgUnit->orgUnitCode}}">
      			</div>

      			<div class="col">
		      	<label>Кассир</label>
	    		<input type="text" disabled="" class="form-control" placeholder="Введите ФИО Сотрудника" value="{{$user->FIO}} ({{$user->username}})">
      			</div>
          	</div>

    		<div class="row">
	  			<div class="col-auto">
		      	<label>Дата поступления</label>
	    		<input required type="date" id="paymentDate" name="paymentDate" class="form-control" placeholder="Введите дату поступления платежа">
      			</div>

    			<div class="col-auto">
		      	<label>Сумма </label>
				<div class="input-group">	            
					<input required type="number" step="0.01" name="paymentSum" id="paymentSum" 
							class="form-control" 
            				placeholder="Введите сумму платежа">
		  			<span class="input-group-text">руб.</span>
		  		</div>
		  		</div>
		  		<div class="col-auto">
		  			<a class="btn btn-success mt-4">
		  			<i class="fas fa-redo"></i>
		  			Полное погашение
		  			</a>
		  		<p>35700.00 руб.</p>
		  		</div>
          	</div>
      	</div>
      	</form>


      </div>
@endsection