@extends('layouts.user')

@section('title')
    Договор займа № {{$loan->loan_number}} от
    {{date(config('app.date_format', 'd-m-Y'), strtotime($loan->loan_conclusion_date))}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
    <a class="btn btn-default"
       href="{{route('loan.index')}}">
        < К списку
    </a>
    <div class="d-flex justify-content-between">
        <h1>Договор займа № {{$loan->loan_number}} от
            {{date(config('app.date_format', 'd-m-Y'), strtotime($loan->loan_conclusion_date))}}
        </h1>
        <div style="margin-top:auto; margin-bottom: auto;">
            <a class="btn btn-secondary" href="#">
                Экспорт документов
            </a>
        </div>
    </div>

    <div class="block-content">
        @if($loan->status_open)
            <a class="btn btn-warning"
               href="{{route('loan.close', ['id' => $loan->id])}}">
                Закрыть договор
            </a>
        @else
            <span class="mb-3 badge bg-danger">Договор займа закрыт</span>
        @endif

        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <ul class="nav nav-tabs" id="blockinfo" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"
                   data-bs-toggle="tab"
                   href="#g-tabs-1"
                   role="tab">
                    Основное
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="tab"
                   href="#g-tabs-2"
                   role="tab">
                    Анкета
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   data-bs-toggle="tab"
                   href="#g-tabs-3"
                   role="tab">
                    Платежи
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="g-tabs-1" role="tabpanel">
                <h4>Основное</h4><br>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Договор</td>
                        <td><p>Договор займа № {{$loan->loan_number}} от
                                {{date(config('app.date_format', 'd-m-Y'), strtotime($loan->loan_conclusion_date))}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>Подразделение</td>
                        <td>{{$loan->orgUnit->org_unit_code}}</td>
                    </tr>
                    <tr>
                        <td>Заемщик</td>
                        <td>{{$loan->clientForm->client->text}}
                        </td>
                    </tr>
                    <tr>
                        <td>Сумма займа</td>
                        <td>{{$loan->clientForm->loan_cost}} руб.</td>
                    </tr>
                    <tr>
                        <td>Процентная ставка</td>
                        <td>{{$loan->clientForm->interest_rate}} %</td>
                    </tr>
                    <tr>
                        <td>Задолженность</td>
                        <td>[Пока не рассчитывается] руб.</td>
                    </tr>
                    <tr>
                        <td>Статус</td>
                        <td>@if($loan->status_open)
                                Открыт
                            @else
                                Закрыт
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="g-tabs-2" role="tabpanel">
                <h4>Анкета</h4>
                <div class="d-flex block-padding">
                    <a class="btn btn-info"
                       target="_blank"
                       href="{{route('clientForm.show', $loan->clientForm->id)}}">
                        Страница анкеты
                    </a>
                </div>

                <x-clientform-info :clientForm="$loan->clientForm"/>
            </div>
            <div class="tab-pane" id="g-tabs-3" role="tabpanel">
                <div class="d-flex justify-content-between">
                    <h4>Платежи</h4>
                    <div class="dropdown" style="margin-top:auto; margin-bottom: auto;">
                        <a class="btn btn-secondary dropdown-toggle"
                           href="#"
                           role="button"
                           id="dropdownMenuLink"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Экспорт
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li>
                                <a class="dropdown-item"
                                   id="exportExcel"
                                   data-export="{{route('user.export')}}">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex block-padding">
                    @if($loan->status_open)
                        <a class="btn btn-primary"
                           href="{{route('payment.create', $loan->id)}}">
                            Внести платеж
                        </a>
                    @endif
                </div>

                <table class="table table-light table-hover">
                    <thead>
                    <tr class="table-primary">
                        <th scope="col">Дата</th>
                        <th scope="col">Сумма платежа</th>
                        <th scope="col">Действия
                    </tr>
                    </thead>
                    <tbody>
                    @if($loan->payments->count() == 0)
                        <tr>
                            <td>
                                <strong>Платежи отсутствуют</strong>
                            </td>
                        </tr>
                    @endif

                    @foreach($loan->payments as $payment)
                        <tr>
                            <td>
                                <strong>
                                    {{date(config('app.date_format', 'd-m-Y'), strtotime($payment->payment_date))}}
                                </strong>
                            </td>
                            <td>
                                {{$payment->payment_sum}} руб.
                            </td>
                            <td>
                                <div class="d-flex manage-btns">

                                    <a class="btn btn-success"
                                       href="{{route('payment.show', $payment->id)}}"
                                       role="button">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form method="POST" action="{{route('payment.destroy', $payment->id)}}">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-danger"
                                                type="submit"
                                                onclick="return confirm('Вы действительно хотите удалить запись?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
