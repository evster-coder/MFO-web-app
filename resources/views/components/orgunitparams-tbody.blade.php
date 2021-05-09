@if($params->total() == 0)
<tr>
  <td colspan="4">
    Ничего не найдено...
  </td>
</tr>
@endif
@foreach($params as $param)
  <tr>
    <td>
      {{$param->name}}
    </td>
    <td>
      {{$param->slug}}
    </td>
    <td>
      {{$param->dataType}}
    </td>
    <td>
        <div class = "d-flex manage-btns">
            <form method="POST" action="{{route('param.destroy', [$param->id]) }}">
              @method('DELETE')
              @csrf

              @perm('edit-orgunit-param')
              <a href="javascript:void(0)" class="btn btn-success" id="edit-data" data-toggle="modal" 
              data-url="orgunitparam/"
              data-id="{{ $param->id }}">Редактирование</a>

              @perm('delete-orgunit-param')
              <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');">Удаление</button>
              @endperm
              @endperm
            </form>
        </div>
    </td>
  </tr>
@endforeach
<tr class="pagination-tr">
  <td colspan="6" align="center">
    <p class="pagination-p">Текущая страница {{$params->currentPage()}} из {{$params->lastPage()}}</p>
    {{ $params->links('pagination::bootstrap-4') }}
  </td>
</tr>
