@if($clientforms->count() == 0)
	<tr>
		<td>
			Отсутствуют заявки
		</td>
	</tr>
@endif
@foreach($clientforms as $clientform)
<tr>
	<td>
		<a href="{{route('clientform.show', ['id' => $clientform->id])}}" class="btn btn-link"><strong>Анкета №{{$clientform->id}} от {{date('d.m.Y', strtotime($clientform->loanDate))}}</strong>
		<i class="fas fa-external-link-alt"></i>
		</a>
	</td>
</tr>
@endforeach