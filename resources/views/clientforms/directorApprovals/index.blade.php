  
@extends('layouts.user')

@section('title')
	История одобрений
@endsection

@push('assets')
    <!--<link rel="stylesheet" href="{{ asset('css/clients.css') }}">-->
@endpush

@section('content')
	<div class="d-flex justify-content-between">
		<h1>История одобрений директора</h1>
		<div class="dropdown" style="margin-top:auto; margin-bottom: auto;">
		  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
		    Экспорт
		  </a>

		  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
		    <li><a class="dropdown-item" id="exportExcel" data-export="{{route('user.export')}}"><i class="fas fa-file-excel"></i> Excel</a></li>
		  </ul>
		</div>
	</div>
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
					<th scope="col">Статус</th>
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
					<td>@if($clientform->DirectorApproval->approval)
						Одобрена
						@else
						Отклонена
						@endif
					</td>
					<td>			
						<div class = "d-flex manage-btns">
		                <!-- Админские кнопки редактирования и удаления -->
		                <a class="btn btn-success" href="{{route('directorApproval.show', ['id' => $clientform->director_approval_id])}}" role="button">
		                	<i class="fas fa-eye"></i>
		        		</a>
		        		@if(!$clientform->Loan)
					        <form method="POST" action="{{route('directorApproval.destroy', [$clientform->DirectorApproval->id])}}">
					          @method('DELETE')
					          @csrf
					          <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');"><i class="fas fa-trash-alt"></i></button>
					        </form>
		        		@endif
    					</div>
    				</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	    <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
	</div>
@endsection