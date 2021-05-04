
@extends('layouts.user')

@section('title')
	Пользователь {{$user->username}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <script src="{{ asset('js/usersCRUD/index.js') }}"></script>
@endpush


@section('content')
	<button type="button" class="btn btn-default" onclick="javascript:history.back()">< Назад</button>

	<h1> Пользователь {{$user->username}}</h1>

	<div class="block-content">

		<div class="block-padding d-flex">
			@perm('edit-user')
          	<a class="btn btn-info" href="{{route('user.edit', [$user->id]) }}" role="button">
            	Редактировать
          	</a>

 			@endperm

			@perm('delete-user')
            <form method="POST" action="{{route('user.destroy', [$user->id]) }}">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-info" onclick="return confirm('Вы действительно хотите удалить запись?');">Удалить</button>
            </form>
            @endperm		

    		@perm('manage-users')
				@if(!$user->blocked)
					<a href="{{route ('user.block', [$user->id])}}" class="btn btn-danger" onclick="return confirm('Вы действительно хотите заблокировать пользователя?');">Заблокировать</a>
				@else
					<a href="{{route ('user.unblock', [$user->id])}}" class="btn btn-info">Разблокировать</a>
				@endif
				<a href="{{route ('user.resetpassword', [$user->id])}}" class="btn btn-info">Сбросить пароль</a>
			@endperm
        </div>

		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />


		<ul class="nav nav-tabs" id="userinfo" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-bs-toggle="tab" href="#tabs-1" role="tab">Основное</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#tabs-2" role="tab">Роли пользователя</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#tabs-3" role="tab">Обслуживаемые займы</a>
			</li>
		</ul><!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="tabs-1" role="tabpanel">
				<h5>Сведения: </h5><br>
				<p>Логин: {{$user->username}}</p>
				<p>ФИО сотрудника: {{$user->username}}</p>
				<p>Статус: 
					@if ($user->blocked)
						<span class="badge bg-danger">ЗАБЛОКИРОВАН</span>
					@else
						<span class="badge bg-success">Активен</span>
					@endif
				</p>
				<p>Подразделение: {{$user->OrgUnit->orgUnitCode}}</p>

				@perm('manage-users')
				<p>Необходимость смены пароля:
					@if($user->needChangePassword)
						Да
					@else
						Нет
					@endif
				</p>
				@endperm

				<p>Должность: {{$user->position}}</p>
				<p>Основание: {{$user->reason}}</p>
				<p>Дата регистрации: {{$user->created_at}}</p>
				<p>Дата обновление данных: {{$user->updated_at}}</p>
			</div>
			<div class="tab-pane" id="tabs-2" role="tabpanel">
				<h5>Пользователь имеет роли: </h5><br>
				<ol>
					@foreach($user->roles as $role)
						<li>{{ $role->name }} ({{$role->slug}})</li>
					@endforeach
				</ol>
			</div>
			<div class="tab-pane" id="tabs-3" role="tabpanel">
				<p>Обслуживаемые займы</p>
			</div>
		</div>

@endsection