
@extends('layouts.user')

@section('title')
	Ваш профиль | {{Auth::user()->username}}
@endsection

@push('assets')
@endpush

@section('content')

		<h1>Ваш профиль</h1>

		<a href="{{route ('user.resetyourpassword')}}" class="btn btn-info">Сбросить пароль</a>

		<div class="block-content block-section">

			<x-auth-session-status class="mb-4" :status="session('status')" />
	    	<x-auth-validation-errors class="mb-4" :errors="$errors" />
				
				<br><h5>Сведения: </h5><br>

				<p>Логин: {{$user->username}}</p>
				<p>ФИО сотрудника: {{$user->FIO}}</p>
				<p>Статус: 
					@if ($user->blocked)
						<span class="badge bg-danger">ЗАБЛОКИРОВАН</span>
					@else
						<span class="badge bg-success">Активен</span>
					@endif
				</p>
				<p>Подразделение: {{$user->OrgUnit->orgUnitCode}}</p>

				@perm('manage-users')
				<p>Необходимость смены пароля:
					@if($user->needChangePassword)
						Да
					@else
						Нет
					@endif
				</p>
				@endperm

				<p>Должность: {{$user->position}}</p>
				<p>Основание: {{$user->reason}}</p>
				<p>Дата регистрации: {{$user->created_at}}</p>
				<p>Дата обновление данных: {{$user->updated_at}}</p>
				<br><h5>Пользователь имеет роли: </h5><br>
				<ol>
					@foreach($user->roles as $role)
						<li>{{ $role->name }} ({{$role->slug}})</li>
					@endforeach
				</ol>
			</div>


	    </div>

@endsection