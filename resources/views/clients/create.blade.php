@extends('layouts.user')

@section('title')
    @if($curClient->exists)
        Редактирование клиента
    @else
        Создание клиента
    @endif
@endsection

@push('assets')
    <script src="{{ asset('js/clientsCRUD/create.js') }}" defer></script>
@endpush

@section('content')
    <h1>
        @if($curClient->exists)
            Редактирование клиента
        @else
            Добавление клиента
        @endif
    </h1>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form action="{{$curClient->exists ? route('client.update', [$curClient->id]) : route('client.store')}}"
              method="POST" @if ($curClient->exists) id="formUpdate" @else id="formCreate" @endif>

            @if($curClient->exists)
                @method('PUT')
            @else
                @method('POST')
            @endif

            @csrf
            <div class="btn-group">
                @if($curClient->exists)
                    <button class="btn btn-success"
                            type="submit"
                            id="updateData">
                        Обновить
                    </button>
                @else
                    <a class="btn btn-success"
                       href=""
                       id="btnAdd"
                       data-update="0"
                       data-url="{{route('client.sameclients')}}">
                        Добавить
                    </a>
                @endif
                <button class="btn btn-default"
                        type="button"
                        onclick="javascript:history.back()">
                    Назад
                </button>
            </div>

            <div id="sameClients">
                <x-same-client/>
            </div>

            <div class="block-section">
                <div class="form-group edit-fields">
                    <label for="surname">Фамилия</label>
                    <input class="form-control"
                           required
                           name="surname"
                           id="surname"
                           type="text"
                           placeholder="Введите Фамилию"
                           value="{{old('surname', $curClient->surname)}}">
                </div>

                <div class="form-group edit-fields">
                    <label for="name">Имя</label>
                    <input class="form-control"
                           required
                           name="name"
                           id="name"
                           type="text"
                           placeholder="Введите Имя"
                           value="{{old('name', $curClient->name)}}">
                </div>

                <div class="form-group edit-fields">
                    <label for="patronymic">Отчество</label>
                    <input class="form-control"
                           name="patronymic"
                           id="patronymic"
                           type="text"
                           placeholder="Введите Отчество"
                           value="{{old('patronymic', $curClient->patronymic)}}">
                </div>

                <div class="form-group edit-fields">
                    <label for="birth_date">Дата рождения</label>
                    <input class="form-control"
                           required
                           name="birth_date"
                           id="birth_date"
                           type="date"
                           placeholder="Введите Дату рождения"
                           value="{{old('birth_date', $curClient->birth_date)}}">
                </div>
            </div>
        </form>
    </div>
@endsection
