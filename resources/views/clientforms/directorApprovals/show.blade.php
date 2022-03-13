@extends('layouts.user')

@section('title')
    Проверка заявки на займ
@endsection

@section('content')
    <a class="btn btn-default"
       href="{{route('directorApproval.index')}}">
        < К списку
    </a>

    <h1>
        Согласование заявки на займ №{{$clientform->id}}
        от {{date(config('app.date_format', 'd-m-Y'), strtotime($clientform->loanDate))}}
    </h1>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>
        <div class="block-section">
            @if($clientform->DirectorApproval)
                <h4>Общая информация</h4>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Статус</td>
                        <td>
                            {{$clientform->DirectorApproval->approval ? "Одобрено" : "Отклонено"}}
                        </td>
                    </tr>
                    <tr>
                        <td>Пользователь</td>
                        <td>
                            <a target="_blank"
                               href="{{route('user.show', ['id' => $clientform->DirectorApproval->User->id])}}">
                                {{$clientform->DirectorApproval->User->username}}
                                ({{$clientform->DirectorApproval->User->FIO}})
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Дата оформления одобрения</td>
                        <td>
                            {{date(config('app.datetime_format', 'd-m-Y H:i:s'),
                                strtotime($clientform->DirectorApproval->approvalDate))}}
                        </td>
                    </tr>
                    <tr>
                        <td>Комментарий пользователя</td>
                        <td>{{$clientform->DirectorApproval->comment}}</td>
                    </tr>
                    </tbody>
                </table>
            @endif
        </div>
        <div class="block-section">
            <h4>Клиент</h4>
            <a class="btn btn-info"
               target="_blank"
               href="{{route('client.show', ['id' => $clientform->client_id])}}">
                Проверка клиента
            </a>
            <hr>
            <table class="table">
                <tbody>
                <tr>
                    <td>Фамилия</td>
                    <td>{{$clientform->Client->surname}}</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>{{$clientform->Client->name}}</td>
                </tr>
                <tr>
                    <td>Отчество</td>
                    <td>{{$clientform->Client->patronymic}}</td>
                </tr>

                <tr>
                    <td>Дата рождения</td>
                    <td>{{date('d.m.Y', strtotime($clientform->Client->birthDate))}}</td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="block-section">
            <h4>Анкета</h4>
            <a class="btn btn-info"
               target="_blank"
               href="{{route('clientform.show', ['id' => $clientform->id])}}">
                Проверка анкеты
            </a>
            <hr>
            <table class="table">
                <tbody>
                <tr>
                    <td>Дата оформления</td>
                    <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientform->loanDate))}}</td>
                </tr>
                <tr>
                    <td>Сумма займа</td>
                    <td>{{$clientform->loanCost}} руб.</td>
                </tr>
                <tr>
                    <td>Срок займа</td>
                    <td>{{$clientform->loanTerm}} дней</td>
                </tr>

                <tr>
                    <td>Процентная ставка</td>
                    <td>{{$clientform->interestRate}} %</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
