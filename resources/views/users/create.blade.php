@extends('layouts.user')

@section('title')
    @if ($curUser->exists)
        Редактирование пользователя
    @else
        Новый пользователь
    @endif
@endsection

@push('assets')
    <script src="{{ asset('js/userManage.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endpush

@section('content')

    <h1>
        @if($curUser->exists)
            Редактирование пользователя
        @else
            Добавление пользователя
        @endif
    </h1>

    <div class="content-block">

        @if($curUser->exists)
            <div class="block-padding">
                @if(!$curUser->blocked)
                    <a href="{{route ('user.block', [$curUser->id])}}" class="btn btn-danger"
                       onclick="return confirm('Вы действительно хотите заблокировать пользователя?');">
                        Заблокировать
                    </a>
                @else
                    <a href="{{route ('user.unblock', [$curUser->id])}}" class="btn btn-info">
                        Разблокировать
                    </a>
                @endif

                <a href="{{route ('user.resetpassword', [$curUser->id])}}" class="btn btn-info">
                    Сбросить пароль
                </a>
            </div>

            <form method="POST"
                  action="{{$curUser->exists ? route('user.update', [$curUser->id]) : route('user.store')}}"
                  @if (!$curUser->exists) id="formStore" @endif>
                @if ($curUser->exists)
                    @method('PUT')
                @else
                    @method('POST')
                @endif
                @csrf
                <x-auth-session-status class="mb-4" :status="session('status')"/>
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        @if($curUser->exists)
                            Сохранить
                        @else
                            Добавить
                        @endif
                    </button>
                    <button type="button" class="btn btn-default" onclick="javascript:history.back()">
                        Назад
                    </button>
                </div>

                <div class="block-section">
                    <h4>Основное</h4>
                    <div class="form-group edit-fields">
                        <label for="username">Логин</label>
                        <input required
                               class="form-control"
                               name="username"
                               id="username"
                               type="text"
                               placeholder="Введите Логин"
                               value="{{ old( 'username', $curUser->username) }}">
                    </div>

                    <div class="form-group edit-fields">
                        <label for="FIO">ФИО</label>
                        <input required
                               class="form-control"
                               name="FIO"
                               id="FIO"
                               type="text"
                               placeholder="Введите ФИО сотрудника"
                               value="{{ old( 'FIO', $curUser->FIO) }}">
                    </div>

                    <div class="form-group edit-fields">
                        <label for="orgunit_id">Подразделение</label>
                        <select2 required
                                 class="form-group"
                                 data-width="100%"
                                 :options="{{$orgUnits}}"
                                 name="orgunit_id"
                                 id="orgunit_id"
                                 value="{{old('orgunit_id', $curUser->orgunit_id)}}">
                            <option value="" disabled> Введите Подразделение</option>
                        </select2>
                    </div>

                    <div class="form-group edit-fields">
                        @if(!$curUser->exists)
                            <label for="password">Пароль</label>
                            <div class="row g-3">
                                <div class="col-sm-10">
                                    <input required
                                           class="form-control"
                                           name="password"
                                           id="password"
                                           type="text"
                                           placeholder="Введите Пароль">
                                    <span toggle="#password"
                                          class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-info" id="random-password">
                                        Случайный
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="form-group edit-fields">
                        <label for="position">Должность</label>
                        <input class="form-control"
                               name="position"
                               id="position"
                               type="text"
                               placeholder="Введите Должность"
                               value="{{ old( 'position', $curUser->position) }}">
                    </div>

                    <div class="form-group edit-fields">
                        <label for="reason">Основание</label>
                        <input class="form-control"
                               name="reason"
                               id="reason"
                               type="text"
                               placeholder="Введите Основание"
                               value="{{ old( 'reason', $curUser->reason) }}">
                    </div>
                </div>
                <!-- Блок редактирования ролей пользователя, если пользователь имеет право присваивать роли -->
                @perm('assign-role')
                <div class="block-section">
                    <h4>Роли пользователя</h4>

                    <div class="form-group edit-fields">
                        @foreach ($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input"
                                       id="{{$role->slug}}"
                                       value="{{$role->slug}}"
                                       type="checkbox"
                                       name="roles[]"
                                       @if( $curUser->exists && $curUser->hasRole($role->slug)) checked
                                    @endif
                                >
                                <label class="form-check-label" for="{{$role->slug}}">
                                    {{$role->name}}
                                </label>
                            </div>

                        @endforeach
                    </div>

                </div>
                @endperm
            </form>
    </div>


@endsection
