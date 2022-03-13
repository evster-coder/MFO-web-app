@if($rates->total() == 0)
    <tr>
        <td colspan="2">
            Ничего не найдено...
        </td>
    </tr>
@endif

@foreach($rates as $rate)
    <tr>
        <td>
            {{$rate->percentValue}}
        </td>
        <td>
            <div class="d-flex manage-btns">
                <form method="POST" action="{{route('interestrate.destroy', [$rate->id]) }}">
                    @method('DELETE')
                    @csrf
                    <a class="btn btn-success"
                       href="javascript:void(0)"
                       id="edit-data"
                       data-toggle="modal"
                       data-url="interestrate/"
                       data-id="{{ $rate->id }}">
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
            Текущая страница {{$rates->currentPage()}} из {{$rates->lastPage()}}
        </p>
        {{ $rates->links('pagination::bootstrap-4') }}
    </td>
</tr>
