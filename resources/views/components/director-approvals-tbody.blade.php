@if($clientforms->total() == 0)
<tr>
  <td colspan="5">
    Ничего не найдено...
  </td>
</tr>
@endif

@foreach($clientforms as $clientform)
<tr>
	<td>№{{$clientform->id}} {{date('d.m.Y', strtotime($clientform->loanDate))}}</td>
	<td>{{date('d.m.Y', strtotime($clientform->DirectorApproval->approvalDate))}}</td>
	<td>{{$clientform->Client->fullName}}</td>
	<td>{{$clientform->loanCost}}</td>
	<td>{{$clientform->User->username}} ({{$clientform->User->FIO}})</td>
	<td>@if($clientform->DirectorApproval->approval)
		Одобрена
		@else
		Отклонена
		@endif
	</td>
	<td>			
		<div class = "d-flex manage-btns">
        <!-- Админские кнопки редактирования и удаления -->
        <a class="btn btn-success" href="{{route('directorApproval.show', ['id' => $clientform->director_approval_id])}}" role="button">
        	<i class="fas fa-eye"></i>
		</a>
		@if(!$clientform->Loan)
	        <form method="POST" action="{{route('directorApproval.destroy', [$clientform->DirectorApproval->id])}}">
	          @method('DELETE')
	          @csrf
	          <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');"><i class="fas fa-trash-alt"></i></button>
	        </form>
		@endif
		</div>
	</td>
</tr>
@endforeach
