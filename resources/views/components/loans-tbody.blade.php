@if($loans->total() == 0)
<tr>
  <td colspan="5">
    Ничего не найдено...
  </td>
</tr>
@endif
@foreach($loans as $loan)
<tr>
	<td>{{$loan->loanNumber}}</td>
	<td>{{$loan->loanConclusionDate}}</td>
	<td>{{$loan->ClientForm->Client->fullName}}</td>
	<td>@if($loan->statusOpen)
			Открыт
		@else
			Закрыт
		@endif
	</td>
	<td>
	<div class = "d-flex manage-btns">
        <!-- Админские кнопки редактирования и удаления -->
        <a class="btn btn-success" href="{{route('loan.show', ['id' => $loan->id])}}" role="button">
        	<i class="fas fa-eye"></i>
		</a>

        @perm('delete-loan')
        <form method="POST" action="{{route('loan.destroy', ['id' => $loan->id])}}">
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
    <p class="pagination-p">Текущая страница {{$loans->currentPage()}} из {{$loans->lastPage()}}</p>
    {{ $loans->links('pagination::bootstrap-4') }}
  </td>
</tr>