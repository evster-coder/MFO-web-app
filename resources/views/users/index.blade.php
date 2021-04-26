
@extends('layouts.user')

@section('title')
	Пользователи системы
@endsection

@section('content')

		<h1>Список категорий авто</h1>

      	<div class="add-car-button">
        	<a class="btn btn-primary" href="{{ route('categories.create') }}" role="button">Добавить</a>
      	</div>

	<table class="table table-bordered mb-5">
		<thead>
			<tr class="table-info">
				<th scope="col">ID</th>
				<th scope="col">Наименование</th>
				<th scope="col">Manage btns</th>
			</tr>
		</thead>
		<tbody>

			@foreach($cats as $cat)
			<tr>
				<th scope="row">{{ $cat->PK_Category }}</th>
				<td>{{ $cat->nameCategory }}</td>
				<td>
					<div class = "d-flex">
		                <!-- Админские кнопки редактирования и удаления -->
		                  <a class="btn btn-primary edit-btn"  href="{{route('categories.edit', $cat->PK_Category) }}" role="button">Изменить</a>
		                <form method="POST" action="{{route('categories.delete', $cat->PK_Category) }}">
		                  @method('DELETE')
		                  @csrf
		                  <button type="submit" class="btn edit" onclick="return confirm('Вы действительно хотите удалить запись?');">Удалить</button>
		                </form>
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