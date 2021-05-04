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

	<tr>
		<td colspan="6" align="center">
			{{ $roles->links('pagination::bootstrap-4') }}
		</td>
	</tr>

