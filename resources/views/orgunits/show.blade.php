@extends('layouts.user')

@section('title')
    Подразделение {{$orgUnit->org_unit_code}}
@endsection

@section('content')
    <a class="btn btn-default" href="{{route('orgunit.index')}}">< К списку</a>

    <h1> Подразделение {{$orgUnit->org_unit_code}}</h1>

    <div class="block-content">

        <div class="block-padding d-flex">
            @perm('edit-orgunit')
            <a class="btn btn-info"
               href="{{route('orgunit.edit', [$orgUnit->id]) }}"
               role="button">
                Редактировать
            </a>

            @endperm

            @perm('delete-orgunit')
            <form method="POST" action="{{route('orgunit.destroy', [$orgUnit->id]) }}">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger"
                        type="submit"
                        onclick="return confirm('Вы действительно хотите удалить подразделение?');">
                    Удалить
                </button>
            </form>
            @endperm
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <ul class="nav nav-tabs" id="blockinfo" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"
                   data-bs-toggle="tab"
                   href="#tabs-1"
                   role="tab">
                    Основное
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="tab"
                   href="#tabs-2"
                   role="tab">
                    Параметры подразделения
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="tab"
                   href="#tabs-3"
                   role="tab">
                    Пользователи
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <h5>Сведения:</h5><br>
                <p>Код: {{$orgUnit->org_unit_code}}</p>
                <p>Справочники:
                    @if($orgUnit->has_dictionaries)
                        Разрешены
                    @else
                        Запрещены
                    @endif
                </p>
                <p>Родительское подразделение:
                    @if($orgUnit->parent)
                        <a class="btn btn-link"
                           href="{{route('orgunit.show', [$orgUnit->parent->id])}}">
                            {{$orgUnit->parent->org_unit_code}}
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    @else
                        Нет
                    @endif
                </p>
                <p>Дочерние подразделения:
                    @if(count($orgUnit->children) == 0)
                        нет
                @else
                    <ul>
                        @foreach ($orgUnit->children as $child)
                            <li>
                                <a class="btn btn-link" href="{{route('orgunit.show', [$child->id])}}">
                                    {{$child->org_unit_code}}
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                    </p>
            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <h5>Значения параметров: </h5><br>
                <table class="table mb-5 table-hover">
                    <thead>
                    <tr>
                        <th>Параметр</th>
                        <th>Значение</th>
                        <th>Источник параметра</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($params as $param)
                        <tr>
                            @if($param->orgUnitParam)
                                <td>{{$param->orgUnitParam->name}}</td>
                                <td>
                                    @if($param->orgUnitParam->data_type == 'date')
                                        {{$param->data_as_date}}
                                    @elseif($param->orgUnitParam->data_type == 'number')
                                        {{$param->data_as_number}}
                                    @elseif($param->orgUnitParam->data_type == 'string')
                                        {{$param->data_as_string}}
                                    @else
                                        - Не установлено
                                    @endif
                                </td>
                                <td align="right">
                                    {{$param->orgUnit->org_unit_code}}
                                </td>
                            @else
                                <td>{{$param->name}}</td>
                                <td>Не установлено</td>
                                <td>-</td>
                        @endif
                        <tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tabs-3" role="tabpanel">
                <h5>Пользователи в подразделении:</h5><br>
                <table class="table mb-5">
                    <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="{{route('user.show', [$user->id])}}">
                                    {{$user->username}} ({{$user->full_name}})
                                </a>
                            </td>
                            <td>
                                @if ($user->blocked)
                                    <span class="badge bg-danger">Заблокирован</span>
                                @else
                                    <span class="badge bg-success">Активен</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection
