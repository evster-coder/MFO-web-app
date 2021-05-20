  
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

		<table class="table client-table mb-5">
			<thead>
				<tr class="table-info">
					<th scope="col">Анкета</th>
					<th scope="col">Клиент</th>
					<th scope="col">Сумма займа</th>
					<th scope="col">Пользователь</th>
					<th scope="col">Действия</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Анкета №123 от 29.05.2021</td>
					<td>Петров Иван Иванович</td>
					<td>550000</td>
					<td>cashier_user</td>
					<td>			
						<div class = "d-flex manage-btns">
		                <!-- Админские кнопки редактирования и удаления -->
		                <a class="btn btn-success" href="{{route('clientform.approval-show')}}" role="button">
		                	<i class="fas fa-eye"></i> Рассмотреть
		        		</a>
    					</div>
    				</td>
				</tr>
			</tbody>
		</table>

	    <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
	</div>
@endsection