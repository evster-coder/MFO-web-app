
@extends('layouts.user')

@section('title')
	Стаж работы
@endsection

@section('content')

		<h1>Стаж работы - список</h1>

		@perm('create-user')
	      	<div class="add-user-btn">
	        	<a class="btn btn-primary" href="{{ route('seniority.create') }}" role="button">
	        		Добавить
        		</a>
	      	</div>
      	@endperm

	<table class="table table-bordered mb-5">
		<thead>
			<tr class="table-info">
				<th scope="col">Логин</th>
				<th scope="col">ФИО</th>
				<th scope="col">Блокировка</th>
				<th scope="col">Роли</th>
				<th scope="col">Действия</th>
			</tr>
		</thead>
		<tbody>

			@foreach($users as $user)
			<tr>
				<td>{{ $user->username }}</td>
				<td>{{ $user->FIO }}</td>
				<td>@if ($user->blocked)
						Да
					@else
						Нет
					@endif
				</td>
				<td>
					<ul>
						@foreach($user->roles as $role)
						<li>{{ $role->name }}</li>
						@endforeach
					</ul>
				</td>

				<td>
					<div class = "d-flex manage-btns">
		                <!-- Админские кнопки редактирования и удаления -->
		                <a class="btn btn-success" href="{{route('user.show', $user)}}" role="button">
		                	Просмотр
	            		</a>

						@perm('edit-user')
	                  	<a class="btn btn-info" href="{{route('user.edit', $user) }}" role="button">
	                  		Изменить
	                  	</a>
	         			@endperm
 	
 						@perm('delete-user')
		                <form method="POST" action="{{route('user.destroy', $user) }}">
		                  @method('DELETE')
		                  @csrf
		                  <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');">Удалить</button>
		                </form>
		                @endperm

            		</div>
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div class="justify-content-center">
		{{ $users->links('pagination::bootstrap-4') }}
	</div>

@endsection