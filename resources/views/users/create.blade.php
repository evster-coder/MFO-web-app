
@extends('layouts.user')

@section('title')
	@if ($curUser->exists)
		Редактирование пользователя
	@else
		Новый пользователь
	@endif
@endsection

@push('assets')
	<script src="{{ asset('js/userManage.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endpush


@section('content')



	@if($curUser->exists)
		<form method="POST" action="{{ route('user.update', $curUser) }}">
			@method('PUT')
	@else
		<form method="POST" action="{{ route('user.store') }}" id="formStore">
			@method('POST')
	@endif

    		<x-auth-session-status class="mb-4" :status="session('status')" />
        	<x-auth-validation-errors class="mb-4" :errors="$errors" />

			<div class="form-group edit-fields">
	            <label for="username">Логин</label>
	            <input required name="username" id="username" type="text" class="form-control" placeholder="Введите Логин" value= "{{ old( 'username', $curUser->username) }}">
          	</div>

			<div class="form-group edit-fields">
	            <label for="FIO">ФИО</label>
	            <input required name="FIO" id="FIO" type="text" class="form-control" placeholder="Введите ФИО сотрудника" value= "{{ old( 'FIO', $curUser->FIO) }}">
          	</div>

			<div class="form-group edit-fields">
	            <label for="password">Пароль</label>
	            <input required name="password" id="password" type="password" class="form-control" placeholder="Введите Пароль" value= "{{ old( 'password', $curUser->password) }}">
              	<span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

          	</div>

			<div class="form-group edit-fields">
	            <label for="position">Должность</label>
	            <input required name="position" id="position" type="text" class="form-control" placeholder="Введите Должность" value= "{{ old( 'position', $curUser->position) }}">
          	</div>

			<div class="form-group edit-fields">
	            <label for="reason">Основание</label>
	            <input required name="reason" id="reason" type="text" class="form-control" placeholder="Введите Основание" value= "{{ old( 'reason', $curUser->reason) }}">
          	</div>

			<div class="btn-group">
				<button type="submit" class="btn btn-success">Сохранить</button>
				<button type="button" class="btn btn-default" onclick="javascript:history.back()">Назад</button>
			</div>


        </form>


@endsection