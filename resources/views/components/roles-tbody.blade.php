@if($roles->total() == 0)
    <tr>
        <td colspan="3">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($roles as $role)
    <tr>
        <td>
            {{$role->name}}
        </td>
        <td>
            {{$role->slug}}
        </td>
        <td>
            <div class="d-flex manage-btns">
                <a class="btn btn-success"
                   href="{{route('role.show', [$role->id])}}"
                   role="button">
                    Подробности
                </a>

                <form method="POST" action="{{route('role.destroy', [$role->id]) }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger"
                            type="submit"
                            onclick="return confirm('Вы действительно хотите удалить запись?');">
                        Удаление
                    </button>
                </form>

            </div>
        </td>
    </tr>
@endforeach

<tr class="pagination-tr">
    <td colspan="6" align="center">
        <p class="pagination-p">
            Текущая страница {{$roles->currentPage()}} из {{$roles->lastPage()}}
        </p>
        {{ $roles->links('pagination::bootstrap-4') }}
    </td>
</tr>

