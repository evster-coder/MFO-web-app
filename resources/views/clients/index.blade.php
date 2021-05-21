  
@extends('layouts.user')

@section('title')
	Справочник клиентов
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
    <script src="{{ asset('js/clientsCRUD/table.js') }}" defer></script>
@endpush

@section('content')
	<h1>Справочник клиентов</h1>
	<div class="content-block">
		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

    	@perm('create-client')
	      	<div class="add-client-btn">
	        	<a class="btn btn-primary" href="{{ route('client.create') }}" role="button">Добавить</a>
	      	</div>
	    @endperm

		<table class="table client-table mb-5">
			<thead>
				<tr class="table-info">
					<th scope="col" class="sorting" 
										data-sorting-type="asc" 
										data-column-name="surname">
			        	<div class="form-group has-search">
			        		<p align="center">Фамилия</p>
						    <span class="fa fa-search form-control-feedback"></span>
			      			<input type="text" name="searchSurname"
			      			 id="searchSurname" class="form-control" 
			      			 placeholder="Поиск..." />
					  	</div>
					</th>
					<th scope="col" class="sorting" 
										data-sorting-type="asc" 
										data-column-name="name">
			        	<div class="form-group has-search">
			        		<p align="center">Имя</p>
			        		<span class="fa fa-search form-control-feedback"></span>
			      			<input type="text" name="searchName"
			      			 id="searchName" class="form-control" 
			      			 placeholder="Поиск..." />
					  	</div>
					</th>
					<th scope="col" class="sorting"
										data-sorting-type="asc" 
										data-column-name="patronymic">
	        			<div class="form-group has-search">
	        				<p align="center">Отчество</p>
						    <span class="fa fa-search form-control-feedback"></span>
			      			<input type="text" name="searchPatronymic" 
			      			id="searchPatronymic" class="form-control" 
			      			placeholder="Поиск..." />
					  	</div>
					</th>
					<th scope="col" align="center"
										data-column-name="birthDate">
			        	<div class=" has-search form-group" style="width:13vw;">
			        		<p align="center">Дата рождения</p>
					    	<span class="fa fa-search form-control-feedback"></span>
			      			<input type="date" name="searchBirthDate"
			      			 id="searchBirthDate" class="form-control"
			      			 placeholder="Поиск..."/>
					  	</div>
					</th>
					<th scope="col"><p align="center">Действия</p></th>
				</tr>
			</thead>
			<tbody>
				<x-clients-tbody :clients="$clients"></x-clients-tbody>
			</tbody>
		</table>

	    <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
	</div>
@endsection