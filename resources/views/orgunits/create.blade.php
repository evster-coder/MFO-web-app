  
@extends('layouts.user')

@section('title')
	@if($currOrgunit->exists)
		Редактирование подразделения
	@else
		Новое подразделение
	@endif
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/orgunits.css') }}">
@endpush

@section('content')
	@if($currOrgunit->exists)
		<h1>Редактирование подразделения</h1>
	@else
		<h1>Новое подразделение</h1>
	@endif

	<div class="content-block">
		@if($currOrgunit->exists)
		<form method="POST" action="{{ route('orgunit.update', [$currOrgunit->id]) }}">
			@method('PUT')
		@else
			<form method="POST" action="{{ route('orgunit.store') }}" id="formStore">
			@method('POST')
		@endif

			@csrf

    		<x-auth-session-status class="mb-4" :status="session('status')" />
        	<x-auth-validation-errors class="mb-4" :errors="$errors" />


			<div class="btn-group">
				<button type="submit" class="btn btn-success">
				@if($currOrgunit->exists)
					Обновить
				@else
					Добавить
				@endif
				</button>
				<button type="button" class="btn btn-default" onclick="javascript:history.back()">Назад</button>
			</div>


        	<div class="block-section">
        		<h4>Основное</h4>
				<div class="form-group edit-fields">
		            <label for="orgUnitCode">Код подразделения</label>
		            <input required name="orgUnitCode" id="orgUnitCode" type="text" class="form-control" placeholder="Введите Код подразделения" value= "{{ old( 'orgUnitCode', $currOrgunit->orgUnitCode) }}">
	          	</div>

	          	<div class="form-group edit-fields">
	          		<label for="parent_id">Родительское подразделение</label>
	          		<select name="parent_id" id="parent_id" type="text" class="form-control">
	          			@if($parent != null)
	          				<option selected value="{{$parent->id}}">
	          					{{$parent->orgUnitCode}}
	          				</option>
	          			@else
	          				<option value="" disabled selected>Отсутствует</option>
	          			@endif
	          		</select>
	          	</div>

				<div class="form-group edit-fields">
		            <label for="hasDictionaries">Справочники</label>
		            <select required name="hasDictionaries" id="hasDictionaries" type="text" class="form-select">
		            	@if($parent)
		            		@if($parent->hasDictionaries)
		            			@if($currOrgunit->exists)
				            		<option value="1"
				            			@if($currOrgunit->hasDictionaries)
				            			selected
				            			@endif
				            		>
				            			Разрешены
				            		</option>
				            		<option value="0"
				            			@if(!$currOrgunit->hasDictionaries)
				            			selected
				            			@endif
			            			>
				            			Запрещены (наследуются)
				            		</option>
				            	@else
				            		<option value="1">			
				            			Разрешены
				            		</option>
				            		<option value="0">			       
				            			Запрещены (наследуются)
				            		</option>
				            	@endif
				            @else
			            		<option value="1" disabled>				       
			            			Разрешены
			            		</option>
			            		<option value="0" selected>
			            			Запрещены (наследуются)
			            		</option>
			            	@endif

		            	@else
		            		@if($currOrgunit->exists)
			            		<option value="1"
			            			@if($currOrgunit->hasDictionaries)
			            			selected
			            			@endif
			            		>
			            			Разрешены
			            		</option>
			            		<option value="0"
			            			@if(!$currOrgunit->hasDictionaries)
			            			selected
			            			@endif
		            			>
			            			Запрещены (наследуются)
			            		</option>
		            		@else
			            		<option value="1">				       
			            			Разрешены
			            		</option>
			            		<option value="0" selected>
			            			Запрещены (наследуются)
			            		</option>
		            		@endif
		            	@endif
		            </select>
	          	</div>

        	</div>

        	<div class="block-section">
        		<h4>Параметры подразделений</h4>

        	</div>
	</div>
@endsection
