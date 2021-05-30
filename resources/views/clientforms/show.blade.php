  @extends('layouts.user')

@section('title')
	Заявка на займ №{{$clientform->id}} от {{$clientform->loanDate}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
	<a href="{{route('clientform.index')}}" class="btn btn-default">< К списку</a>
	<h1>Заявка на займ №{{$clientform->id}} от {{$clientform->loanDate}}</h1>

	<div class="block-content">
		<div class="d-flex block-padding">
			<p class="me-3">Статус:
				{{$clientform->status}}
			</p>
			@if($clientform->Loan)
			<a href="{{route('loan.show', ['id' => $clientform->Loan->id])}}" class="btn btn-primary me-3">Перейти к договору</a>
			@else
				@perm('delete-clientform')
				<a href="{{route('clientform.destroy', 
				['id' => $clientform->id])}}" class="btn btn-danger">Удалить</a>
				@endperm

				@if($clientform->status == 'Одобрена')
					@perm('create-loan')
					<form method="POST" action="{{route('loan.store')}}">
						@csrf
						<input type="hidden" name="clientform_id" value="{{$clientform->id}}">
						<button type="submit" class="btn btn-primary me-3">
							Заключить договор
						</button>
					</form>
					@endperm
				@endif

			@endif
		</div>
		
		<x-clientform-info :clientform="$clientform"></x-clientform-info>


	</div>
	     
@endsection