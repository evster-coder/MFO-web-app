@extends('layouts.user')

@section('title')
	Заявки на рассмотрение
@endsection

@push('assets')
    <!--<link rel="stylesheet" href="{{ asset('css/clients.css') }}">-->
@endpush

@section('content')
	<h1>Заявки, ожидающие рассмотрения</h1>
	<div class="content-block">
		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

		<table class="table mb-5">
			<thead>
				<tr class="table-info">
					<th scope="col">№ заявки</th>
					<th scope="col">Дата</th>
					<th scope="col">Клиент</th>
					<th scope="col">Сумма займа</th>
					<th scope="col">Пользователь</th>
					<th scope="col">Действия</th>
				</tr>
			</thead>
			<tbody>
				@foreach($clientforms as $clientform)
				<tr>
					<td>{{$clientform->id}}</td>
					<td>{{date('d-m-Y', strtotime($clientform->loanDate))}}</td>
					<td>{{$clientform->Client->fullName}}</td>
					<td>{{$clientform->loanCost}}</td>
					<td>{{$clientform->User->username}} ({{$clientform->User->FIO}})</td>
					<td>			
						<div class = "d-flex manage-btns">
		                <!-- Админские кнопки редактирования и удаления -->
		                <a class="btn btn-success" href="{{route('securityApproval.create', ['id' => $clientform->id])}}" role="button">
		                	<i class="fas fa-eye"></i> Рассмотреть
		        		</a>
    					</div>
    				</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	    <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
	</div>
@endsection