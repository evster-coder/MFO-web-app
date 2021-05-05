
@extends('layouts.user')

@section('title')
	Управление ролями
@endsection

@push('assets')
    <script src="{{ asset('js/rolesCRUD/table.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/roles.css') }}">
@endpush

@section('content')
	<h1>Управление ролями</h1>
	<div class="content-block">

		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

      	<div class="add-role-btn">
        	<a class="btn btn-primary" href="{{ route('role.create') }}" role="button">Добавление</a>
              <div class="form-group has-search">
              <span class="fa fa-search form-control-feedback"></span>
                <input type="text" name="search" id="search" class="form-control" placeholder="Выполните поиск..." />
            </div>
      	</div>

      	<table class="table table-bordered role-table">
            <thead>
            <tr class="table-primary">
              <th scope="col" class="sorting" data-sorting-type="asc" 
                            data-column-name="name">Роль <span id="icon-name"></span></th>
              <th scope="col"  class="sorting" data-sorting-type="asc" 
                            data-column-name="slug">Сокращение <span id="icon-slug"></span></th>
              <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
              <x-roles-tbody :roles="$roles"></x-roles-tbody>
          </tbody>
      	</table>
        <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
        <input type="hidden" name="hiddenSortColumn" id="hiddenSortColumn" value="name" />
        <input type="hidden" name="hiddenSortDesc" id="hiddenSortDesc" value="desc" />

    </div>
@endsection