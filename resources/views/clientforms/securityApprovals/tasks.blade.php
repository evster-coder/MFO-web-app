@extends('layouts.user')

@section('title')
    Заявки на рассмотрение
@endsection

@section('content')
    <h1>Заявки, ожидающие рассмотрения</h1>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>
        <table class="table mb-5">
            <thead>
            <tr class="table-info">
                <th scope="col">№ заявки</th>
                <th scope="col">Дата</th>
                <th scope="col">Клиент</th>
                <th scope="col">Сумма займа</th>
                <th scope="col">Пользователь</th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clientForms as $clientForm)
                <tr>
                    <td>{{$clientForm->id}}</td>
                    <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->loan_date))}}</td>
                    <td>{{$clientForm->client->fullName}}</td>
                    <td>{{$clientForm->loan_cost}}</td>
                    <td>{{$clientForm->user->username}} ({{$clientForm->user->full_name}})</td>
                    <td>
                        <div class="d-flex manage-btns">
                            <a class="btn btn-success"
                               href="{{route('securityApproval.create', ['id' => $clientForm->id])}}"
                               role="button">
                                <i class="fas fa-eye"></i> Рассмотреть
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <input type="hidden" name="hiddenPage" id="hiddenPage" value="1"/>
    </div>
@endsection
