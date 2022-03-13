@extends('layouts.user')

@section('title')
    Проверка заявки на займ
@endsection

@push('assets')
    <script src="{{ asset('js/clientformsCRUD/approvals/create.js') }}" defer></script>
@endpush

@section('content')
    <a class="btn btn-default"
       href="{{route('securityApproval.tasks')}}">
        < К списку
    </a>
    <h1>Согласование заявки на займ №{{$clientform->id}}
        от {{date(config('app.date_format', 'd-m-Y'), strtotime($clientform->loanDate))}}</h1>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <div class="block-padding d-flex">
            @if(!$clientform->security_approval_id)
                <form method="POST"
                      action="{{route('securityApproval.accept')}}"
                      id="accept">
                    @csrf
                    <input type="hidden" name="clientform_id" value="{{$clientform->id}}">
                    <input type="hidden" name="comment">
                    <button class="btn btn-primary"
                            type="submit">
                        Одобрить
                    </button>
                </form>
                <form method="POST"
                      action="{{route('securityApproval.reject')}}"
                      id="reject">
                    @csrf
                    <input type="hidden" name="clientform_id" value="{{$clientform->id}}">
                    <input type="hidden" name="comment">
                    <button class="btn btn-warning" type="submit">
                        Отклонить
                    </button>
                </form>
            @endif
        </div>

        <div class="block-section">
            <h4>Комментарий</h4>
            <textarea class="form-control"
                      id="commentField">
            </textarea>
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
               href="{{route('clientform.show',['id' => $clientform->id])}}">
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
