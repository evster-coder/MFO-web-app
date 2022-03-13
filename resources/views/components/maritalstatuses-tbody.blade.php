@if($statuses->total() == 0)
    <tr>
        <td colspan="2">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($statuses as $mst)
    <tr>
        <td>
            {{$mst->name}}
        </td>
        <td>
            <div class="d-flex manage-btns">
                <form method="POST" action="{{route('maritalstatus.destroy', [$mst->id]) }}">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-success"
                       href="javascript:void(0)"
                       id="edit-data"
                       data-toggle="modal"
                       data-url="maritalstatus/"
                       data-id="{{ $mst->id }}">Редактирование</a>
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
            Текущая страница {{$statuses->currentPage()}} из {{$statuses->lastPage()}}
        </p>
        {{ $statuses->links('pagination::bootstrap-4') }}
    </td>
</tr>
