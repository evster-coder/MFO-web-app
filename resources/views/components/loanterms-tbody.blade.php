@if($terms->total() == 0)
    <tr>
        <td colspan="2">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($terms as $loanTerm)
    <tr>
        <td>
            {{$loanTerm->days_amount}}
        </td>
        <td>
            <div class="d-flex manage-btns">
                <form method="POST" action="{{route('loanterm.destroy', [$loanTerm->id]) }}">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-success"
                       href="javascript:void(0)"
                       id="edit-data"
                       data-toggle="modal"
                       data-url="loanterm/"
                       data-id="{{ $loanTerm->id }}">
                        Редактирование
                    </a>

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
            Текущая страница {{$terms->currentPage()}} из {{$terms->lastPage()}}
        </p>
        {{ $terms->links('pagination::bootstrap-4') }}
    </td>
</tr>
