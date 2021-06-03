  @extends('layouts.user')

@section('title')
	Договор займа № 123321 от 29.05.2020
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
	<a href="{{route('loan.index')}}" class="btn btn-default">< К списку</a>
	<h1>Договор займа № {{$loan->loanNumber}} от {{date('d-m-Y', strtotime($loan->loanConclusionDate))}}</h1>

	<div class="block-content">

		@if($loan->statusOpen)
		<a class="btn btn-warning" href="{{route('loan.close', ['id' => $loan->id])}}">Закрыть договор</a>
		@else
			<span class="mb-3 badge bg-danger">ДОГОВОР ЗАЙМА ЗАКРЫТ</span>
		@endif

		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

		<ul class="nav nav-tabs" id="blockinfo" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-bs-toggle="tab" href="#g-tabs-1" role="tab">Основное</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#g-tabs-2" role="tab">Анкета</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#g-tabs-3" role="tab">Платежи</a>
			</li>
		</ul><!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="g-tabs-1" role="tabpanel">
	    		<h4>Основное</h4><br>
		        <table class="table">
		          <tbody>
		            <tr>
		              <td>Договор</td>
		              <td><p>Договор займа № {{$loan->loanNumber}} от {{date('d-m-Y', strtotime($loan->loanConclusionDate))}}</p>
		              </td>
		            </tr>
		            <tr>
		              <td>Подразделение</td>
		              <td>{{$loan->OrgUnit->orgUnitCode}}</td>
		            </tr>
		            <tr>
		              <td>Заемщик</td>
		              <td>{{$loan->ClientForm->Client->text}}
		              </td>
		            </tr>
		            <tr>
		              <td>Сумма займа</td>
		              <td>{{$loan->ClientForm->loanCost}} руб.</td>
		            </tr>
		            <tr>
		              <td>Процентная ставка</td>
		              <td>{{$loan->ClientForm->interestRate}} %</td>
		            </tr>
		            </tr>
		            <tr>
		              <td>Задолженность</td>
		              <td>[Пока не рассчитывается] руб.</td>
		            </tr>            
		            <tr>
		              <td>Статус</td>
		              <td>@if($loan->statusOpen)
		              		Открыт
		              	@else
		              		Закрыт
		              	@endif
		              </td>
		            </tr>
		          </tbody>
		        </table>
			</div>
			<div class="tab-pane" id="g-tabs-2" role="tabpanel">
	    		<h4>Анкета</h4>
	    		<div class="d-flex block-padding">
	    			<a target="_blank" href="{{route('clientform.show', $loan->ClientForm->id)}}" class="btn btn-info">Страница анкеты</a>
	       	 	</div>

	    			<x-clientform-info :clientform="$loan->ClientForm"></x-clientform-info>
			</div>
			<div class="tab-pane" id="g-tabs-3" role="tabpanel">
				<div class="d-flex justify-content-between">
					<h4>Платежи</h4>
					<div class="dropdown" style="margin-top:auto; margin-bottom: auto;">
					  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
					    Экспорт
					  </a>

					  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
					    <li><a class="dropdown-item" id="exportExcel" data-export="{{route('user.export')}}"><i class="fas fa-file-excel"></i> Excel</a></li>
					  </ul>
					</div>
				</div>

	    		<div class="d-flex block-padding">
	    			@if($loan->statusOpen)
	    			<a 
	    			href="{{route('payment.create', $loan->id)}}"
	    			class="btn btn-primary">Внести платеж</a>
	    			@endif
	       	 	</div>

		    	<table class="table table-light table-hover">
		    		<thead>
						<tr class="table-primary">
							<th scope="col">Дата</th>
							<th scope="col">Сумма платежа</th>
							<th scope="col">Действия
						</tr>
		    		</thead>
		    		<tbody>
		    			@if($loan->Payments)
		    				<tr><strong>
		    					Платежи отсутствуют
		    				</strong></tr>
		    			@endif

		    			@foreach($loan->Payments as $payment)
			    		<tr>
				    		<td>
			    				<strong> {{date('d.m.Y', strtotime($payment->paymentDate))}} </strong>
			    			</td>
			    			<td>
			    				{{$payment->paymentSum}} руб.
			    			</td>
			    			<td>
							<div class = "d-flex manage-btns">
			                <!-- Админские кнопки редактирования и удаления -->
			                <a class="btn btn-success" 
			                href="{{route('payment.show', $payment->id)}}" 
			                role="button">
			                	<i class="fas fa-eye"></i>
			        		</a>

			                <form method="POST" action="{{route('payment.destroy', $payment->id)}}">
			                  @method('DELETE')
			                  @csrf
			                  <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');"><i class="fas fa-trash-alt"></i></button>
			                </form>

    						</div>
			    			</td>
						</tr>
						@endforeach
			    	</tbody>
				</table>
			</div>

		</div>
	</div>
	     
@endsection