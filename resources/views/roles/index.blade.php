
@extends('layouts.user')

@section('title')
	Управление ролями
@endsection

@push('assets')

@endpush

@section('content')
	<h1>Управление ролями</h1>
	<div class="content-block">

		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

      	<div class="add-user-btn">
        	<a class="btn btn-primary" href="{{ route('role.create') }}" role="button" 
                  style="margin:10px;">Добавление</a>
      	</div>

      	<table class="table table-bordered table-hover">
            <thead>
            <tr class="table-primary">
              <th scope="col">Роль</th>
              <th scope="col">Сокращение</th>
              <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
        		@foreach($roles as $role)
            <tr>
              <td>
        			 {{$role->name}}
              </td>
              <td>
                {{$role->slug}}
              </td>
              <td>
                <div class = "d-flex manage-btns">
                  <a class="btn btn-success" href="{{route('role.show', [$role->id])}}" role="button">
                    Подробности
                  </a>

                  <form method="POST" action="{{route('role.destroy', [$role->id]) }}">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');">Удаление</button>
                  </form>

                </div>
              </td>
            </tr>
        		@endforeach
          </tbody>
      	</table>

    </div>
@endsection