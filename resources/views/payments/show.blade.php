@extends('layouts.user')

@section('title')
    Платеж от {{date(config('app.date_format', 'd-m-Y'), strtotime($payment->paymentDate))}}
    по договору займа №{{$payment->Loan->LoanNumber}}
    от {{date(config('app.date_format', 'd-m-Y'), strtotime($payment->Loan->loanConclusionDate))}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
    <a class="btn btn-default"
       href="{{route('loan.show', $payment->Loan->id)}}">
        < К договору займа
    </a>
    <div class="d-flex justify-content-between">
        <h1>Подробности платежа</h1>
        <div style="margin-top:auto; margin-bottom: auto;">
            <a class="btn btn-secondary" href="#">
                Экспорт документов
            </a>
        </div>
    </div>

    <div class="block-content">
        <div class="block-padding d-flex">
            <form method="POST" action="{{route('payment.destroy', $payment->id)}}">
                @method('DELETE')
                @csrf
                <button class="btn btn-info"
                        type="submit"
                        onclick="return confirm('Вы действительно хотите удалить запись?');">
                    Удалить
                </button>
            </form>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <div class="block-section">
            <h4>Платеж</h4>
            <table class="table">
                <tbody>
                <tr>
                    <td>Договор</td>
                    <td><a href="{{route('loan.show', $payment->Loan->id)}}">
                            <p>Договор №{{$payment->Loan->LoanNumber}}
                                от {{date(config('app.date_format', 'd-m-Y'), strtotime($payment->Loan->loanConclusionDate))}}
                                <i class="fas fa-external-link-alt"></i>
                            </p>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Подразделение</td>
                    <td>{{$payment->OrgUnit->orgUnitCode}}</td>
                </tr>
                <tr>
                    <td>Клиент</td>
                    <td><a href="{{route('client.show', $payment->Loan->ClientForm->Client->id)}}"><p>
                                {{$payment->Loan->ClientForm->Client->fullName}}
                                <i class="fas fa-external-link-alt"></i></p></a>
                    </td>
                </tr>
                <tr>
                    <td>Сумма платежа</td>
                    <td>{{$payment->paymentSum}} руб.</td>
                </tr>
                <tr>
                    <td>Дата поступления платежа</td>
                    <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($payment->paymentDate))}}</td>
                </tr>
                <tr>
                    <td>Пользователь</td>
                    <td>{{$payment->User->FIO}} ({{$payment->User->username}})</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
