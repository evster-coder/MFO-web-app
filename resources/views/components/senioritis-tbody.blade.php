@if($senioritis->total() == 0)
<tr>
  <td colspan="2">
    Ничего не найдено...
  </td>
</tr>
@endif
@foreach($senioritis as $seniority)
  <tr>
    <td>
      {{$seniority->name}}
    </td>
    <td>
        <div class = "d-flex manage-btns">
            <form method="POST" action="{{route('seniority.destroy', [$seniority->id]) }}">
              @method('DELETE')
              @csrf
              <a href="javascript:void(0)" class="btn btn-success" id="edit-data" data-toggle="modal" 
              data-url="seniority/"
              data-id="{{ $seniority->id }}">Редактирование</a>

              <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');">Удаление</button>
            </form>
        </div>
    </td>
  </tr>
@endforeach
<tr class="pagination-tr">
  <td colspan="6" align="center">
    <p class="pagination-p">Текущая страница {{$senioritis->currentPage()}} из {{$senioritis->lastPage()}}</p>
    {{ $senioritis->links('pagination::bootstrap-4') }}
  </td>
</tr>
