  
@extends('layouts.user')

@section('title')
	@if($curClient->exists)
		Редактирование клиента
	@else
		Создание клиента
	@endif
@endsection

@push('assets')
    <script src="{{ asset('js/clientsCRUD/create.js') }}" defer></script>
@endpush

@section('content')
	@if($curClient->exists)
		<h1> Редактирование клиента</h1>
	@else
		<h1> Добавление клиента</h1>
	@endif	

	<div class="content-block">
		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

    	@if($curClient->exists)
    	<form action="{{route('client.update', [$curClient->id])}}" method="POST" id="formUpdate">
    		@method('PUT')
    	@else
    	<form action="{{route('client.store')}}" method="POST" id="formCreate">
    		@method('POST')
    	@endif
			@csrf
			<div class="btn-group">
				@if($curClient->exists)
					<button type="submit" class="btn btn-success" id="updateData">
						Обновить
					</button>
				@else
					<a href="" id="btnAdd" data-update="0" data-url="{{route('client.sameclients')}}" class="btn btn-success">Добавить</a>
				@endif
				<button type="button" class="btn btn-default" onclick="javascript:history.back()">Назад</button>
			</div>

			<div id="sameClients">
				<x-same-client></x-same-client>
			</div>

	    	<div class="block-section">
				<div class="form-group edit-fields">
		            <label for="surname">Фамилия</label>
		            <input required name="surname" id="surname" type="text" 
		            	class="form-control" placeholder="Введите Фамилию" 
		            	value="{{ old('surname', $curClient->surname) }}">
		      	</div>

				<div class="form-group edit-fields">
		            <label for="name">Имя</label>
		            <input required name="name" id="name" type="text" 
		            	class="form-control" placeholder="Введите Имя"
	            		value="{{old('name', $curClient->name)}}">
		      	</div>

				<div class="form-group edit-fields">
		            <label for="patronymic">Отчество</label>
		            <input name="patronymic" id="patronymic" type="text"
		            	 class="form-control" placeholder="Введите Отчество"
		            	 value="{{old('patronymic', $curClient->patronymic)}}">
		      	</div>

				<div class="form-group edit-fields">
		            <label for="birthDate">Дата рождения</label>
		            <input required name="birthDate" id="birthDate" type="date"
		             	class="form-control" placeholder="Введите Дату рождения"
		             	value="{{old('birthDate', $curClient->birthDate)}}">
		      	</div>

	    	</div>
    	</form>

	</div>
@endsection