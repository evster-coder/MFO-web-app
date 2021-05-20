  
@extends('layouts.user')

@section('title')
	Создание платежа
@endsection

@push('assets')
@endpush

@section('content')
	<h1>Создание платежа по договору займа №123321 от 29.05.2020</h1>
	<div class="content-block">
		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

		<div class="btn-group">
			<button type="submit" class="btn btn-success">
				Создать
			</button>
			<a href="{{route('loan.show')}}" type="button" class="btn btn-default"> К договору займа</a>
		</div>

    	<div class="block-section">
    		<h4>Договор</h4>
    		<div class="row">
	  			<div class="col">
		      	<label>Номер договора</label>
	    		<input type="text" readonly="" class="form-control" placeholder="Введите номер договора" value="123321">
      			</div>

      			<div class="col">
		      	<label>Дата </label>
	    		<input type="date" readonly="" class="form-control" placeholder="Выберите дату договора" value="2020-05-30">
	    		</div>
          	</div>
      	</div>

    	<div class="block-section">
    		<h4>Платеж</h4>
    		<div class="row">

      			<div class="col">
		      	<label>Подразделение</label>
	    		<input type="text" readonly class="form-control" placeholder="Введите Подразделение" value="S-121">
      			</div>

      			<div class="col">
		      	<label>Кассир</label>
	    		<input type="text" readonly class="form-control" placeholder="Введите ФИО Сотрудника" value="Cashier D.Y.">
      			</div>
          	</div>

    		<div class="row">
	  			<div class="col-auto">
		      	<label>Дата поступления</label>
	    		<input type="date" class="form-control" placeholder="Введите дату поступления платежа">
      			</div>

    			<div class="col-auto">
		      	<label>Сумма </label>
				<div class="input-group">	            
					<input type="number" step="0.01" class="form-control" 
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


      </div>
@endsection