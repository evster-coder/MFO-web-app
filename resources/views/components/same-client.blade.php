@if($clients && $clients->count())
	<div class="block-section">
	<h4>Имеются похожие записи:</h4>
	<table class="table">
		<thead>
			<tr>
				<th>ФИО, дата рождения</th>
				<th>Паспорт</th>
			</tr>
		</thead>
		<tbody>
			@foreach($clients as $client)
			<tr>
				<td>
					<a target="_blank" href="{{route('client.show', $client->id)}}"
						class="btn btn-link">
						{{$client->surname}} {{$client->name}} {{$client->patronymic}}, {{date("d-m-Y", strtotime($client->birthDate))}}
					</a>
				</td>
				<td>
					@if($client->clientForms->count())
					@php
						$lastClientForm = $client->clientForms->last()
					@endphp
						{{$lastClientForm->passportSeries}} {{$lastClientForm->passportNumber}} от {{$lastClientForm->passportDateIssue}}. Выдан: {{$lastClientForm->passportIssuedBy}}
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	</div>
@endif