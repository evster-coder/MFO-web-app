@if($perms->total() == 0)
<tr>
  <td colspan="3">
    Ничего не найдено...
  </td>
</tr>
@endif
@foreach($perms as $perm)
	<tr>
		<td>
			{{$perm->name}}
      	</td>
      	<td>
            {{$perm->slug}}
      	</td>
      	<td>
            <div class = "d-flex manage-btns">
                <form method="POST" action="{{route('perm.destroy', [$perm->id]) }}">
                  @method('DELETE')
                  @csrf
                  <a href="javascript:void(0)" class="btn btn-success" id="edit-perm" data-toggle="modal" data-id="{{ $perm->id }}">Редактирование</a>

                  <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');">Удаление</button>
              	</form>

            </div>
      	</td>
	</tr>
@endforeach
<tr class="pagination-tr">
  <td colspan="6" align="center">
    <p class="pagination-p">Текущая страница {{$perms->currentPage()}} из {{$perms->lastPage()}}</p>
    {{ $perms->links('pagination::bootstrap-4') }}
  </td>
</tr>
