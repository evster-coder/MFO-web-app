<ul class="nav nav-tabs mt-3" id="blockinfo" role="tablist">
    <li class="nav-item">
        <a class="nav-link active"
           data-bs-toggle="tab"
           href="#tabs-1"
           role="tab">
            Клиент
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           data-bs-toggle="tab"
           href="#tabs-2"
           role="tab">
            Паспортные данные
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           data-bs-toggle="tab"
           href="#tabs-3"
           role="tab">
            Адрес проживания
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           data-bs-toggle="tab"
           href="#tabs-4"
           role="tab">
            Семейное положение
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           data-bs-toggle="tab"
           href="#tabs-5"
           role="tab">
            Информация о работе
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           data-bs-toggle="tab"
           href="#tabs-6"
           role="tab">
            Информация о займе
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           data-bs-toggle="tab"
           href="#tabs-7"
           role="tab">
            Прочее
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tabs-1" role="tabpanel">
        <h5>Основные сведения:</h5><br>
        <table class="table">
            <tbody>
            <tr>
                <td>Клиент</td>
                <td>
                    <a target="_blank"
                       href="{{route('client.show', ['id' => $clientForm->client_id])}}">
                        {{$clientForm->client->surname}}  {{$clientForm->client->name}}  {{$clientForm->client->patronymic}}
                    </a>
                </td>
            </tr>
            <tr>
                <td>Дата рождения</td>
                <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->client->birth_date))}}</td>
            </tr>
            <tr>
                <td>Мобильный телефон</td>
                <td>{{$clientForm->mobile_phone}}</td>
            </tr>
            <tr>
                <td>Домашний телефон</td>
                <td>{{$clientForm->home_phone}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="tabs-2" role="tabpanel">
        <h5>Паспортные данные:</h5><br>
        <table class="table">
            <tbody>
            <tr>
                <td>Серия</td>
                <td>{{$clientForm->passport->passport_series}}</td>
            </tr>
            <tr>
                <td>Номер</td>
                <td>{{$clientForm->passport->passport_number}}</td>
            </tr>
            <tr>
                <td>Дата выдачи</td>
                <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->passport->passport_date_issue))}}</td>
            </tr>
            <tr>
                <td>Кем выдан</td>
                <td>{{$clientForm->passport->passport_issued_by}}</td>
            </tr>
            <tr>
                <td>Код подразделения</td>
                <td>{{$clientForm->passport_department_code}}</td>
            </tr>
            <tr>
                <td>Место рождения</td>
                <td>{{$clientForm->passport->passport_birthplace}}</td>
            </tr>
            <tr>
                <td>СНИЛС</td>
                <td>{{$clientForm->snils}}</td>
            </tr>
            <tr>
                <td>Пенсионное удостоверение №</td>
                <td>{{$clientForm->pensioner_id}}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="tabs-3" role="tabpanel">
        <h5>Адрес проживания: </h5><br>
        <table class="table">
            <tbody>
            <tr>
                <td>По паспорту</td>
                <td>{{$clientForm->passport_residence_address}}</td>
            </tr>
            <tr>
                <td>Фактически</td>
                <td>{{$clientForm->actual_residence_address}}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="tabs-4" role="tabpanel">
        <h5>Информация о семейном положении:</h5><br>
        <table class="table">
            <tbody>
            <tr>
                <td>Семейное положение</td>
                <td>{{$clientForm->maritalStatus->name}}</td>
            </tr>
            <tr>
                <td>Количество детей</td>
                <td>{{$clientForm->number_of_dependents}}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="tabs-5" role="tabpanel">
        <h5>Информация о работе: </h5><br>
        <table class="table">
            <tbody>
            <tr>
                <td>Наименование организации</td>
                <td>{{$clientForm->work_place_name}}</td>
            </tr>
            <tr>
                <td>Адрес работы</td>
                <td>{{$clientForm->work_place_address}}</td>
            </tr>
            <tr>
                <td>Должность</td>
                <td>{{$clientForm->work_place_position}}</td>
            </tr>
            <tr>
                <td>Рабочий телефон</td>
                <td> {{$clientForm->work_place_phone}}</td>
            </tr>

            <tr>
                <td>Постоянный доход</td>
                <td>{{$clientForm->constain_income}} руб.</td>
            </tr>

            <tr>
                <td>Дополнительный доход</td>
                <td>{{$clientForm->additional_income}} руб.</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="tabs-6" role="tabpanel">
        <h5>Информация о займе:</h5><br>
        <table class="table">
            <tbody>
            <tr>
                <td>Сумма займа</td>
                <td>{{$clientForm->loan_cost}} руб.</td>
            </tr>
            <tr>
                <td>Процентная ставка</td>
                <td>{{$clientForm->interest_rate}} %</td>
            </tr>
            <tr>
                <td>Срок займа</td>
                <td>{{$clientForm->loan_term}} дней</td>
            </tr>
            <tr>
                <td>Дата оформления</td>
                <td>{{date(config('app.date_format', 'd-m-Y'), strtotime($clientForm->loan_date))}}</td>
            </tr>
            <tr>
                <td>Предполагаемая дата погашения</td>
                <td>{{date(config('app.date_format', 'd-m-Y'),
                    strtotime("+". $clientForm->loan_term ."days", strtotime($clientForm->loan_date)))}}</td>
            </tr>
            <tr>
                <td>Имеются ли действующие кредиты, займы:</td>
                <td>
                    {{$clientForm->has_credits ? 'Да' : 'Нет'}}
                </td>
            </tr>
            <tr>
                <td>Ежемесячный платеж:</td>
                <td>@if($clientForm->monthly_payment)
                        {{$clientForm->monthly_payment}} руб.
                    @else
                        -
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="tabs-7" role="tabpanel">
        <h5>Прочее:</h5><br>
        <table class="table">
            <tbody>
            <tr>
                <td>Наличие банкротства</td>
                <td>
                    @if($clientForm->is_bankrupt)
                        да
                    @else
                        нет
                    @endif
                </td>
            </tr>
            <tr>
                <td>Комментарий кассира</td>
                <td>{{$clientForm->cashier_comment}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
