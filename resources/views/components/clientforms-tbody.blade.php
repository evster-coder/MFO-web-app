@if($clientforms->total() == 0)
<tr>
  <td colspan="5">
    Ничего не найдено...
  </td>
</tr>
@endif

@foreach($clientforms as $clientform)
<tr>
	<td>{{$clientform->id}}</td>
	<td>{{date("d-m-Y", strtotime($clientform->loanDate))}}</td>
	<td>{{$clientform->Client->surname}} {{$clientform->Client->name}} {{$clientform->client->patronymic}} ({{date("d-m-Y", strtotime($clientform->client->birthDate))}})</td>
	<td>{{$clientform->status}}</td>
	<td>
	<div class = "d-flex manage-btns">
        <!-- Админские кнопки редактирования и удаления -->
        <a class="btn btn-success" href="{{route('clientform.show', $clientform->id)}}" role="button">
        	<i class="fas fa-eye"></i>
		</a>

	     @perm('edit-clientform')
      	<a class="btn btn-info" 
      		href="{{route('clientform.edit', $clientform->id)}}" role="button">
        	<i class="fas fa-edit"></i>
      	</a>
  	   @endperm

      @perm('delete-clientform')
        <form method="POST" action="{{route('clientform.destroy', $clientform->id)}}">
          @method('DELETE')
          @csrf
          <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');"><i class="fas fa-trash-alt"></i></button>
        </form>
      @endperm

	</div>
	</td>
</tr>
@endforeach
<tr class="pagination-tr">
  <td colspan="6" align="center">
    <p class="pagination-p">Текущая страница {{$clientforms->currentPage()}} из {{$clientforms->lastPage()}}</p>
    {{ $clientforms->links('pagination::bootstrap-4') }}
  </td>
</tr>
