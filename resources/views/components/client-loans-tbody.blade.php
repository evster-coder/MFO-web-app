@if($loans->count() == 0)
	<tr>
		<td>
		Отсутствуют займы
		</td>
	</tr>
@endif
@foreach($loans as $loan)
	<tr>
		<td>
			<a href="{{route('loan.show', $loan->id)}}" class="btn btn-link"><strong>
    			Договор № {{$loan->loanNumber}} от {{date('d.m.Y', strtotime($loan->loanConclusionDate))}}
			</strong>
			<i class="fas fa-external-link-alt"></i>
			</a>
		</td>
	</tr>
@endforeach