@extends('layouts.user')

@section('title')
    Создание платежа
@endsection

@section('content')
    <h1>Создание платежа по договору займа №{{$loan->loanNumber}}
        от {{date(config('app.date_format', 'd-m-Y'), strtotime($loan->loanConclusionDate))}}</h1>
    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{route('payment.store')}}">
            @method('POST')
            @csrf
            <div class="btn-group">
                <button class="btn btn-success" type="submit">
                    Создать
                </button>
                <a class="btn btn-default"
                   href="{{route('loan.show', $loan->id)}}"
                   type="button">
                    К договору займа
                </a>
            </div>

            <input type="hidden" id="loan_id" name="loan_id" value="{{$loan->id}}">

            <div class="block-section">
                <h4>Договор</h4>
                <div class="row">
                    <div class="col">
                        <label>Номер договора</label>
                        <input class="form-control"
                               type="text"
                               disabled=""
                               placeholder="Введите номер договора"
                               value="{{$loan->loanNumber}}">
                    </div>

                    <div class="col">
                        <label>Дата </label>
                        <input class="form-control"
                               type="date"
                               disabled=""
                               placeholder="Выберите дату договора"
                               value="{{$loan->loanConclusionDate}}">
                    </div>
                </div>
            </div>

            <div class="block-section">
                <h4>Платеж</h4>
                <div class="row">
                    <div class="col">
                        <label>Подразделение</label>
                        <input class="form-control"
                               type="text"
                               disabled=""
                               placeholder="Введите Подразделение"
                               value="{{$user->OrgUnit->orgUnitCode}}">
                    </div>

                    <div class="col">
                        <label>Кассир</label>
                        <input class="form-control"
                               type="text"
                               disabled=""
                               placeholder="Введите ФИО Сотрудника"
                               value="{{$user->FIO}} ({{$user->username}})">
                    </div>
                </div>

                <div class="row">
                    <div class="col-auto">
                        <label>Дата поступления</label>
                        <input class="form-control"
                               required
                               type="date"
                               id="paymentDate"
                               name="paymentDate"
                               placeholder="Введите дату поступления платежа">
                    </div>

                    <div class="col-auto">
                        <label>Сумма</label>
                        <div class="input-group">
                            <input class="form-control"
                                   required
                                   type="number"
                                   step="0.01"
                                   name="paymentSum"
                                   id="paymentSum"
                                   placeholder="Введите сумму платежа">
                            <span class="input-group-text">руб.</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a class="btn btn-success mt-4">
                            <i class="fas fa-redo"></i>
                            Полное погашение
                        </a>
                        <p>35700.00 руб.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
