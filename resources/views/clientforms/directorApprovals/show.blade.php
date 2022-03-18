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
        Согласование заявки на займ №{{$clientForm->id}}
        от {{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->loan_date))}}
    </h1>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>
        <div class="block-section">
            @if($clientForm->directorApproval)
                <h4>Общая информация</h4>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Статус</td>
                        <td>
                            {{$clientForm->directorApproval->approval ? "Одобрено" : "Отклонено"}}
                        </td>
                    </tr>
                    <tr>
                        <td>Пользователь</td>
                        <td>
                            <a target="_blank"
                               href="{{route('user.show', ['id' => $clientForm->directorApproval->user->id])}}">
                                {{$clientForm->directorApproval->user->username}}
                                ({{$clientForm->directorApproval->user->full_name}})
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Дата оформления одобрения</td>
                        <td>
                            {{date(config('app.datetime_format', 'd-m-Y H:i:s'),
                                strtotime($clientForm->directorApproval->approval_date))}}
                        </td>
                    </tr>
                    <tr>
                        <td>Комментарий пользователя</td>
                        <td>{{$clientForm->directorApproval->comment}}</td>
                    </tr>
                    </tbody>
                </table>
            @endif
        </div>
        <div class="block-section">
            <h4>Клиент</h4>
            <a class="btn btn-info"
               target="_blank"
               href="{{route('client.show', ['id' => $clientForm->client_id])}}">
                Проверка клиента
            </a>
            <hr>
            <table class="table">
                <tbody>
                <tr>
                    <td>Фамилия</td>
                    <td>{{$clientForm->client->surname}}</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>{{$clientForm->client->name}}</td>
                </tr>
                <tr>
                    <td>Отчество</td>
                    <td>{{$clientForm->client->patronymic}}</td>
                </tr>

                <tr>
                    <td>Дата рождения</td>
                    <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->client->birth_date))}}</td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="block-section">
            <h4>Анкета</h4>
            <a class="btn btn-info"
               target="_blank"
               href="{{route('clientForm.show', ['id' => $clientForm->id])}}">
                Проверка анкеты
            </a>
            <hr>
            <table class="table">
                <tbody>
                <tr>
                    <td>Дата оформления</td>
                    <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->loan_date))}}</td>
                </tr>
                <tr>
                    <td>Сумма займа</td>
                    <td>{{$clientForm->loan_cost}} руб.</td>
                </tr>
                <tr>
                    <td>Срок займа</td>
                    <td>{{$clientForm->loan_term}} дней</td>
                </tr>

                <tr>
                    <td>Процентная ставка</td>
                    <td>{{$clientForm->interest_rate}} %</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
