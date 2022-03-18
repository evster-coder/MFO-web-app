@if($users->total() == 0)
    <tr>
        <td colspan="6">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($users as $user)
    <tr>
        <td>{{ $user->username }}</td>
        <td>{{ $user->full_name }}</td>
        <td>{{ $user->orgUnit ? $user->orgUnit->org_unit_code : "" }}</td>
        <td>{{$user->blocked ? 'Да' : 'Нет'}}</td>
        <td>
            <ul>
                @foreach($user->roles as $role)
                    <li>-{{ $role->name }}</li>
                @endforeach
            </ul>
        </td>

        <td>
            <div class="d-flex manage-btns">
                <a class="btn btn-success"
                   href="{{route('user.show', [$user->id])}}"
                   role="button">
                    <i class="fas fa-eye"></i>
                </a>
                @perm('edit-user')
                <a class="btn btn-info"
                   href="{{route('user.edit', [$user->id]) }}"
                   role="button">
                    <i class="fas fa-edit"></i>
                </a>
                @endperm
                @perm('delete-user')
                <form method="POST" action="{{route('user.destroy', [$user->id]) }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger"
                            type="submit"
                            onclick="return confirm('Вы действительно хотите удалить запись?');">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
                @endperm
            </div>
        </td>
    </tr>
@endforeach

<tr class="pagination-tr">
    <td colspan="6" align="center">
        <p class="pagination-p">
            Текущая страница {{$users->currentPage()}} из {{$users->lastPage()}}
        </p>
        {{ $users->links('pagination::bootstrap-4') }}
    </td>
</tr>
