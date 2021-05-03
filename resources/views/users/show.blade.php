
@extends('layouts.user')

@section('title')
	Пользователь {{$user->username}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <script src="{{ asset('js/usersCRUD/index.js') }}"></script>
@endpush


@section('content')

	<h1> Пользователь {{$user->username}}</h1>

	<div class="block-content">
		@perm('edit-user')
		<div class="block-padding">
			@if(!$user->blocked)
				<a href="{{route ('user.block', $user->id)}}" class="btn btn-danger" onclick="return confirm('Вы действительно хотите заблокировать пользователя?');">Заблокировать</a>
			@else
				<a href="{{route ('user.unblock', $user->id)}}" class="btn btn-info">Разблокировать</a>
			@endif
			<a href="{{route ('user.resetpassword', $user->id)}}" class="btn btn-success">Сбросить пароль</a>
		</div>
		@endperm

		{{$user->FIO}}
	</div>

@endsection