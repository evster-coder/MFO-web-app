
@extends('layouts.user')

@section('title')
	Пользователи системы
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <script src="{{ asset('js/usersCRUD/index.js') }}"></script>
@endpush

@section('content')

		<h1>Пользователи системы:</h1>

		@perm('create-user')
	      	<div class="add-user-btn">
	        	<a class="btn btn-primary" href="{{ route('user.create') }}" role="button">Добавить</a>
	        	<div class="form-group has-search">
				    <span class="fa fa-search form-control-feedback"></span>
	      			<input type="text" name="search" id="search" class="form-control" placeholder="Выполните поиск..." />
			  	</div>
	      	</div>
      	@endperm

		<table class="table user-table table-bordered mb-5">
			<thead>
				<tr class="table-info">

					<th scope="col" class="sorting" data-sorting-type="asc" 
													data-column-name="username">Логин
												<span id="icon-username"></span></th>
					<th scope="col" class="sorting" 
										data-sorting-type="asc" 
										data-column-name="FIO">ФИО
												<span id="icon-FIO"></span></th>
					<th scope="col" class="sorting"
										data-sorting-type="asc" 
										data-column-name="orgunits.orgUnitCode">Подразделение
												<span id="icon-orgunit"></span></th>
					<th scope="col" class="sorting" 
										data-sorting-type="asc" 
										data-column-name="blocked">Блокировка
												<span id="icon-blocked"></span></th>
					<th scope="col">Роли</th>
					<th scope="col">Действия</th>
				</tr>
			</thead>
			<tbody>
				<x-users-tbody :users="$users"></x-users-tbody>
			</tbody>
		</table>

	    <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
    	<input type="hidden" name="hiddenSortColumn" id="hiddenSortColumn" value="username" />
    	<input type="hidden" name="hiddenSortDesc" id="hiddenSortDesc" value="desc" />

@endsection