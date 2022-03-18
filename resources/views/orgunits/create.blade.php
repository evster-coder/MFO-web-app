@extends('layouts.user')

@section('title')
    @if($currOrgUnit->exists)
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
        @if($currOrgUnit->exists)
            Редактирование подразделения
        @else
            Новое подразделение
        @endif
    </h1>

    <div class="content-block">
        <form method="POST"
              action="{{ $currOrgUnit->exists ? route('orgunit.update', [$currOrgUnit->id]) : route('orgunit.store')}}">
            @if($currOrgUnit->exists)
                @method('PUT')
            @else
                @method('POST')
            @endif
            @csrf
            <x-auth-session-status class="mb-4" :status="session('status')"/>
            <x-auth-validation-errors class="mb-4" :errors="$errors"/>

            <div class="btn-group">
                <button type="submit" class="btn btn-success">
                    @if($currOrgUnit->exists)
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
                    <label for="org_unit_code">Код подразделения</label>
                    <input class="form-control"
                           required
                           name="org_unit_code"
                           id="org_unit_code"
                           type="text"
                           placeholder="Введите Код подразделения"
                           value="{{ old( 'org_unit_code', $currOrgUnit->org_unit_code) }}">
                </div>

                <div class="form-group edit-fields">
                    <label for="parent_id">Родительское подразделение</label>
                    <select class="form-control"
                            name="parent_id"
                            id="parent_id"
                            type="text">
                        @if($parent != null)
                            <option selected value="{{$parent->id}}">
                                {{$parent->org_unit_code}}
                            </option>
                        @else
                            <option value="" disabled selected>Отсутствует</option>
                        @endif
                    </select>
                </div>

                <div class="form-group edit-fields">
                    <label for="has_dictionaries">Справочники</label>
                    <select class="form-select"
                            required
                            name="has_dictionaries"
                            id="has_dictionaries"
                            type="text">
                        @if($parent)
                            @if($parent->has_dictionaries)
                                @if($currOrgUnit->exists)
                                    <option value="1"
                                            @if($currOrgUnit->has_dictionaries)
                                            selected
                                        @endif
                                    >
                                        Разрешены
                                    </option>
                                    <option value="0"
                                            @if(!$currOrgUnit->has_dictionaries)
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
                            @if($currOrgUnit->exists)
                                <option value="1"
                                        @if($currOrgUnit->has_dictionaries)
                                        selected
                                    @endif
                                >
                                    Разрешены
                                </option>
                                <option value="0"
                                        @if(!$currOrgUnit->has_dictionaries)
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
                            @if($param->orgUnitParam)
                                <td>{{$param->orgUnitParam->name}}</td>
                            @else
                                <td>{{$param->name}}</td>
                            @endif

                            <td>
                                <div class="form-group edit-fields">
                                    @if($param->orgUnitParam)
                                        @if($param->org_unit_id == $currOrgUnit->id)
                                            <div class="input-group mb-3">
                                                <div class="input-group-text">
                                                    <input class="form-check-input mt-0"
                                                           name="params_cb[]"
                                                           type="checkbox"
                                                           value="{{$param->org_unit_param_id}}">
                                                    <label for="params_cb[]" class="ms-2">Удалить</label>
                                                </div>
                                                @if($param->orgUnitParam->data_type == 'number')
                                                    <input class="form-control"
                                                           type="number"
                                                           name="params[{{$param->orgUnitParam->slug}}]"
                                                           value="{{$param->data_as_number}}">
                                                @elseif($param->orgUnitParam->data_type == 'date')
                                                    <input class="form-control"
                                                           type="date"
                                                           name="params[{{$param->orgUnitParam->slug}}]"
                                                           value="{{$param->data_as_date}}">
                                                @else
                                                    <input class="form-control"
                                                           type="text"
                                                           name="params[{{$param->orgUnitParam->slug}}]"
                                                           value="{{$param->data_as_string}}">
                                                @endif
                                            </div>
                                        @else
                                            <div class="input group mb-3">
                                                @if($param->orgUnitParam->data_type == 'number')
                                                    <input class="form-control"
                                                           type="number"
                                                           name="params[{{$param->orgUnitParam->slug}}]"
                                                           placeholder="Наследуется {{$param->data_as_number}}">
                                                @elseif($param->orgUnitParam->data_type == 'date')
                                                    <input class="form-control"
                                                           type="date"
                                                           name="params[{{$param->orgUnitParam->slug}}]"
                                                           placeholder="Наследуется {{$param->data_as_date}}">
                                                @else
                                                    <input class="form-control"
                                                           type="text"
                                                           name="params[{{$param->orgUnitParam->slug}}]"
                                                           placeholder="Наследуется {{$param->data_as_string}}">
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="input group mb-3">
                                            @if($param->data_type == 'number')
                                                <input class="form-control"
                                                       type="number"
                                                       name="params[{{$param->slug}}]">
                                            @elseif($param->data_type == 'date')
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
        </form>
    </div>
@endsection
