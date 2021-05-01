@foreach($users as $user)
	<tr>
		<td>{{ $user->username }}</td>
		<td>{{ $user->FIO }}</td>
		<td>{{ $user->orgUnit ? $user->orgUnit->orgUnitCode : "" }}</td>
		<td>@if ($user->blocked)
				Да
			@else
				Нет
			@endif
		</td>
		<td>
			<ul>
				@foreach($user->roles as $role)
				<li>-{{ $role->name }}</li>
				@endforeach
			</ul>
		</td>

		<td>
			<div class = "d-flex manage-btns">
                <!-- Админские кнопки редактирования и удаления -->
                <a class="btn btn-success" href="{{route('user.show', $user)}}" role="button">
                	<i class="fas fa-eye"></i>
        		</a>

				@perm('edit-user')
              	<a class="btn btn-info" href="{{route('user.edit', $user) }}" role="button">
                	<i class="fas fa-edit"></i>
              	</a>
     			@endperm

					@perm('delete-user')
                <form method="POST" action="{{route('user.destroy', $user) }}">
                  @method('DELETE')
                  @csrf
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');"><i class="fas fa-trash-alt"></i></button>
                </form>
                @endperm

    		</div>
		</td>
	</tr>
@endforeach

	<tr>
		<td colspan="6" align="center">
			{{ $users->links('pagination::bootstrap-4') }}
		</td>
	</tr>

