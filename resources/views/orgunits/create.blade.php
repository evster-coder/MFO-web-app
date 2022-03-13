@extends('layouts.user')

@section('title')
    @if($currOrgunit->exists)
        Редактирование подразделения
    @else
        Новое подразделение
    @endif
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/orgunits.css') }}">
    <script src="{{ asset('js/orgunitscreate.js') }}" defer></script>
@endpush

@section('content')
    <h1>
        @if($currOrgunit->exists)
            Редактирование подразделения
        @else
            Новое подразделение
        @endif
    </h1>

    <div class="content-block">
        <form method="POST"
              action="{{ $currOrgunit->exists ? route('orgunit.update', [$currOrgunit->id]) : route('orgunit.store')}}">
            @if($currOrgunit->exists)
                @method('PUT')
            @else
                @method('POST')
            @endif
            @csrf
            <x-auth-session-status class="mb-4" :status="session('status')"/>
            <x-auth-validation-errors class="mb-4" :errors="$errors"/>

            <div class="btn-group">
                <button type="submit" class="btn btn-success">
                    @if($currOrgunit->exists)
                        Обновить
                    @else
                        Добавить
                    @endif
                </button>
                <button class="btn btn-default"
                        type="button"
                        onclick="javascript:history.back()">
                    Назад
                </button>
            </div>

            <div class="block-section">
                <h4>Основное</h4>
                <div class="form-group edit-fields">
                    <label for="orgUnitCode">Код подразделения</label>
                    <input class="form-control"
                           required
                           name="orgUnitCode"
                           id="orgUnitCode"
                           type="text"
                           placeholder="Введите Код подразделения"
                           value="{{ old( 'orgUnitCode', $currOrgunit->orgUnitCode) }}">
                </div>

                <div class="form-group edit-fields">
                    <label for="parent_id">Родительское подразделение</label>
                    <select class="form-control"
                            name="parent_id"
                            id="parent_id"
                            type="text">
                        @if($parent != null)
                            <option selected value="{{$parent->id}}">
                                {{$parent->orgUnitCode}}
                            </option>
                        @else
                            <option value="" disabled selected>Отсутствует</option>
                        @endif
                    </select>
                </div>

                <div class="form-group edit-fields">
                    <label for="hasDictionaries">Справочники</label>
                    <select class="form-select"
                            required
                            name="hasDictionaries"
                            id="hasDictionaries"
                            type="text">
                        @if($parent)
                            @if($parent->hasDictionaries)
                                @if($currOrgunit->exists)
                                    <option value="1"
                                            @if($currOrgunit->hasDictionaries)
                                            selected
                                        @endif
                                    >
                                        Разрешены
                                    </option>
                                    <option value="0"
                                            @if(!$currOrgunit->hasDictionaries)
                                            selected
                                        @endif
                                    >
                                        Запрещены (наследуются)
                                    </option>
                                @else
                                    <option value="1">
                                        Разрешены
                                    </option>
                                    <option value="0">
                                        Запрещены (наследуются)
                                    </option>
                                @endif
                            @else
                                <option value="1" disabled>
                                    Разрешены
                                </option>
                                <option value="0" selected>
                                    Запрещены (наследуются)
                                </option>
                            @endif

                        @else
                            @if($currOrgunit->exists)
                                <option value="1"
                                        @if($currOrgunit->hasDictionaries)
                                        selected
                                    @endif
                                >
                                    Разрешены
                                </option>
                                <option value="0"
                                        @if(!$currOrgunit->hasDictionaries)
                                        selected
                                    @endif
                                >
                                    Запрещены (наследуются)
                                </option>
                            @else
                                <option value="1">
                                    Разрешены
                                </option>
                                <option value="0" selected>
                                    Запрещены (наследуются)
                                </option>
                            @endif
                        @endif
                    </select>
                </div>

            </div>

            <div class="block-section">
                <h4>Параметры подразделения</h4>
                <table class="table table-hover" id="table-params">
                    <tbody>
                    @foreach($params as $param)
                        <tr>
                            @if($param->OrgUnitParam)
                                <td>{{$param->OrgUnitParam->name}}</td>
                            @else
                                <td>{{$param->name}}</td>
                            @endif

                            <td>
                                <div class="form-group edit-fields">
                                    @if($param->OrgUnitParam)
                                        @if($param->orgunit_id == $currOrgunit->id)
                                            <div class="input-group mb-3">
                                                <div class="input-group-text">
                                                    <input class="form-check-input mt-0"
                                                           name="params_cb[]"
                                                           type="checkbox"
                                                           value="{{$param->orgunit_param_id}}">
                                                    <label for="params_cb[]" class="ms-2">Удалить</label>
                                                </div>
                                                @if($param->OrgUnitParam->dataType == 'number')
                                                    <input class="form-control"
                                                           type="number"
                                                           name="params[{{$param->OrgUnitParam->slug}}]"
                                                           value="{{$param->dataAsNumber}}">
                                                @elseif($param->OrgUnitParam->dataType == 'date')
                                                    <input class="form-control"
                                                           type="date"
                                                           name="params[{{$param->OrgUnitParam->slug}}]"
                                                           value="{{$param->dataAsDate}}">
                                                @else
                                                    <input class="form-control"
                                                           type="text"
                                                           name="params[{{$param->OrgUnitParam->slug}}]"
                                                           value="{{$param->dataAsString}}">
                                                @endif
                                            </div>
                                        @else
                                            <div class="input group mb-3">
                                                @if($param->OrgUnitParam->dataType == 'number')
                                                    <input class="form-control"
                                                           type="number"
                                                           name="params[{{$param->OrgUnitParam->slug}}]"
                                                           placeholder="Наследуется {{$param->dataAsNumber}}">
                                                @elseif($param->OrgUnitParam->dataType == 'date')
                                                    <input class="form-control"
                                                           type="date"
                                                           name="params[{{$param->OrgUnitParam->slug}}]"
                                                           placeholder="Наследуется {{$param->dataAsDate}}">
                                                @else
                                                    <input class="form-control"
                                                           type="text"
                                                           name="params[{{$param->OrgUnitParam->slug}}]"
                                                           placeholder="Наследуется {{$param->dataAsString}}">
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="input group mb-3">
                                            @if($param->dataType == 'number')
                                                <input class="form-control"
                                                       type="number"
                                                       name="params[{{$param->slug}}]">
                                            @elseif($param->dataType == 'date')
                                                <input class="form-control"
                                                       type="date"
                                                       name="params[{{$param->slug}}]">
                                            @else
                                                <input class="form-control"
                                                       type="text"
                                                       name="params[{{$param->slug}}]">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
    </div>
@endsection
