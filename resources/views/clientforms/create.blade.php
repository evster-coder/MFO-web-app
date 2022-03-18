@extends('layouts.user')

@section('title')
    @if($clientForm->exists)
        Редактирование заявки на выдачу займа
    @else
        Создание заявки на выдачу займа
    @endif
@endsection

@push('assets')
    <script src="{{ asset('js/clientformsCRUD/create.js') }}" defer></script>
@endpush


@section('content')
    <h1>
        @if($clientForm->exists)
            Редактирование заявки на выдачу займа
        @else
            Создание заявки на выдачу займа
        @endif
    </h1>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form action="{{$clientForm->exists ?
                            route('clientForm.update', ['id' => $clientForm->id]) : route('clientForm.store')}}"
              method="POST">
            @if($clientForm->exists)
                @method('PUT')
            @else
                @method('POST')
            @endif
            @csrf
            <div class="btn-group">
                <button class="btn btn-success"
                        type="submit"
                        id="saveClientform">
                    @if($clientForm->exists)
                        Обновить
                    @else
                        Создать
                    @endif
                </button>
                <button class="btn btn-default"
                        type="button"
                        onclick="javascript:history.back()">
                    Назад
                </button>
            </div>
            <div class="block-section">
                <h4>Клиент</h4>
                <div class="form-group edit-fields">
                    <label for="client_id">Клиент</label>
                    <select2 class="form-group"
                             required
                             data-width="100%"
                             :options="{{$clients}}"
                             name="client_id"
                             id="client_id"
                             value="{{old('client_id', $clientForm->client_id)}}">
                    </select2>
                    <div class="wrapper" id="wrp" style="display: none;">
                        <a class="btn btn-link"
                           href="#"
                           id="addClient"
                           data-bs-toggle="modal"
                           data-bs-target="#crud-modal">
                            + Новый клиент
                        </a>
                    </div>
                </div>
            </div>

            <div class="block-section">
                <h4>Основное</h4>
                <div class="row">
                    <div class="col">
                        <label>Ф.И.О.</label>
                        <input class="form-control"
                               disabled
                               type="text"
                               id="clientFIO"
                               placeholder="ФИО">
                    </div>

                    <div class="col">
                        <label>Дата рождения</label>
                        <input class="form-control"
                               disabled
                               type="date"
                               id="clientBirth_date"
                               placeholder="Дата рождения">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Мобильный телефон</label>
                        <input class="form-control"
                               type="text"
                               name="mobile_phone"
                               id="mobile_phone"
                               placeholder="Введите мобильный телефон"
                               value="{{old('mobile_phone', $clientForm->mobile_phone)}}">
                    </div>
                    <div class="col">
                        <label>Домашний телефон</label>
                        <input class="form-control"
                               type="text"
                               name="home_phone"
                               id="home_phone"
                               placeholder="Введите домашний телефон"
                               value="{{old('home_phone', $clientForm->home_phone)}}">
                    </div>
                </div>
            </div>

            <div class="block-section">
                <h4>Паспортные данные</h4>
                <div class="row">
                    <div class="col">
                        <label>Серия</label>
                        <input class="form-control"
                               required
                               type="text"
                               name="passport_series"
                               id="passport_series"
                               placeholder="Серия"
                               value="{{old('passport_series', $clientForm->passport ?
                                        $clientForm->passport->passport_series : null )}}">
                    </div>
                    <div class="col">
                        <label>Номер</label>
                        <input class="form-control"
                               required
                               type="text"
                               name="passport_number" id="passport_number"
                               value="{{old('passport_number', $clientForm->passport ?
                                        $clientForm->passport->passport_number : null)}}"
                               placeholder="Номер">
                    </div>
                    <div class="col">
                        <label>Дата выдачи</label>
                        <input class="form-control"
                               required
                               type="date"
                               name="passport_date_issue"
                               id="passport_date_issue"
                               placeholder="Дата выдачи"
                               value="{{old('passport_date_issue', $clientForm->passport ?
                                        $clientForm->passport->passport_date_issue : null)}}">
                    </div>
                </div>

                <div class="form-group edit-fields">
                    <label>Кем выдан</label>
                    <textarea class="form-control"
                              required
                              id="passport_issued_by"
                              name="passport_issued_by">
                        {{old('passport_issued_by', $clientForm->passport ?
                          $clientForm->passport->passport_issued_by : null)}}
                    </textarea>
                </div>

                <div class="form-group edit-fields">
                    <label>Код подразделения</label>
                    <input class="form-control"
                           type="text"
                           name="passport_department_code"
                           id="passport_department_code"
                           value="{{old('passport_department_code', $clientForm->passport ?
		            			    $clientForm->passport->passport_department_code : null)}}"
                           placeholder="Введите код подразделения">
                </div>

                <div class="form-group edit-fields">
                    <label>Место рождения</label>
                    <textarea class="form-control"
                              id="passport_birthplace"
                              name="passport_birthplace">
                        {{old('passport_birthplace', $clientForm->passport ?
		            	$clientForm->passport->passport_birthplace : null)}}
                    </textarea>
                </div>

                <div class="row">
                    <div class="col">
                        <label>СНИЛС</label>
                        <input class="form-control"
                               type="text"
                               name="snils"
                               id="snils"
                               placeholder="Введите СНИЛС"
                               value="{{old('snils', $clientForm->snils)}}">
                    </div>
                    <div class="col">
                        <label>Пенсионное удостоверение №</label>
                        <input class="form-control"
                               type="text"
                               name="pensioner_id"
                               id="pensioner_id"
                               value="{{old('pensioner_id', $clientForm->pensioner_id)}}"
                               placeholder="Введите пенсионное удостоверение">
                    </div>
                </div>
            </div>

            <div class="block-section">
                <h4>Адрес проживания</h4>
                <div class="form-group edit-fields">
                    <label>По паспорту</label>
                    <textarea class="form-control"
                              required
                              name="passport_residence_address"
                              id="passport_residence_address">
                        {{old('passport_residence_address', $clientForm->passport_residence_address)}}
                    </textarea>
                </div>

                <div class="form-group edit-fields">
                    <label>Фактически</label>
                    <textarea class="form-control"
                              required
                              id="actual_residence_address"
                              name="actual_residence_address">
                        {{old('actual_residence_address', $clientForm->actual_residence_address)}}
                    </textarea>
                </div>
            </div>

            <div class="block-section">
                <h4>Информация о семейном положении</h4>
                <div class="row">
                    <div class="col">
                        <label>Семейное положение</label>
                        <select class="form-select"
                                required
                                name="marital_status_id"
                                id="marital_status_id">
                            @foreach($maritalStatuses as $maritalStatus)
                                <option @if ( $maritalStatus->id == $clientForm->marital_status_id )
                                        selected
                                        @endif
                                        id="marital_status_id"
                                        name="marital_status_id"
                                        value=" {{ $maritalStatus->id }} ">
                                    {{$maritalStatus->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Количество детей</label>
                        <input class="form-control"
                               type="number"
                               name="number_of_dependents"
                               id="number_of_dependents"
                               placeholder="Количество иждивенцев"
                               value="{{old('number_of_dependents', $clientForm->number_of_dependents)}}"
                               min="0">
                    </div>
                </div>
            </div>

            <div class="block-section">
                <h4>Информация о работе</h4>
                <div class="form-group edit-fields">
                    <label>Наименование организации</label>
                    <input class="form-control"
                           required
                           type="text"
                           name="work_place_name"
                           id="work_place_name"
                           placeholder="Введите наименование организации"
                           value="{{old('work_place_name', $clientForm->work_place_name)}}">
                </div>
                <div class="form-group edit-fields">
                    <label>Адрес работы</label>
                    <textarea class="form-control"
                              name="work_place_address"
                              id="work_place_address">
                        {{old('work_place_address', $clientForm->work_place_address)}}
                    </textarea>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Должность</label>
                        <input class="form-control"
                               type="text"
                               name="work_place_position"
                               id="work_place_position"
                               value="{{old('work_place_position', $clientForm->work_place_position)}}"
                               placeholder="Введите должность">
                    </div>
                    <div class="col">
                        <label>Стаж на месте работы</label>
                        <select class="form-select"
                                required
                                name="seniority_id"
                                id="seniority_id">
                            @foreach($seniorities as $seniority)
                                <option @if ($seniority->id == $clientForm->seniority_id)
                                        selected
                                        @endif
                                        id="seniority_id"
                                        name="seniority_id"
                                        value="{{ $seniority->id }}">
                                    {{$seniority->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group edit-fields">
                    <label>Рабочий телефон</label>
                    <input class="form-control"
                           type="text"
                           name="work_place_phone"
                           id="work_place_phone"
                           placeholder="Введите рабочий телефон"
                           value="{{old('work_place_phone', $clientForm->work_place_phone)}}">
                </div>
                <div class="row">
                    <div class="col">
                        <label>Основной доход</label>
                        <div class="input-group">
                            <input class="form-control"
                                   required
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   placeholder="Введите основной доход"
                                   name="constain_income"
                                   id="constain_income"
                                   value="{{old('constain_income', $clientForm->constain_income)}}">
                            <span class="input-group-text">руб.</span>
                        </div>
                    </div>
                    <div class="col">
                        <label>Дополнительный доход</label>
                        <div class="input-group">
                            <input class="form-control"
                                   required
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   name="additional_income"
                                   id="additional_income"
                                   placeholder="Введите доп. доход"
                                   value="{{old('additional_income', $clientForm->additional_income)}}">
                            <span class="input-group-text">руб.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="block-section">
                <h4>Информация о займе</h4>
                <div class="row">
                    <div class="col">
                        <label>Сумма займа</label>
                        <div class="input-group">
                            <input class="form-control"
                                   required
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   name="loan_cost"
                                   id="loan_cost"
                                   placeholder="Сумма займа"
                                   value="{{old('loan_cost', $clientForm->loan_cost)}}">
                            <span class="input-group-text">руб.</span>
                        </div>
                    </div>
                    <div class="col">
                        <autocomplete-component
                            type="number"
                            required
                            rusname="Срок займа"
                            name="days_amount"
                            path="{{route('loanterm.axiosList')}}"
                            id="loan_term"
                            group-text="дней"
                            given-value="{{old('loan_term', $clientForm->loan_term)}}"
                            selector="loan_term"
                        />
                    </div>

                    <div class="col">
                        <autocomplete-component
                            type="number"
                            required
                            rusname="Процентная ставка"
                            name="percent_value"
                            path="{{route('interestrate.axiosList')}}"
                            id="interest_rate"
                            group-text="%"
                            step="0.001"
                            given-value="{{old('interest_rate', $clientForm->interest_rate)}}"
                        />

                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <label>Дата оформления</label>
                        <input class="form-control"
                               required
                               type="date"
                               name="loan_date"
                               id="loan_date"
                               placeholder="Введите дату оформления"
                               value="{{old('loan_date', $clientForm->loan_date)}}">
                    </div>
                    <div class="col">
                        <label>Дата погашения</label>
                        <input class="form-control"
                               disabled
                               type="date"
                               id="loan_maturity_date"
                               name="loan_maturity_date"
                               placeholder="Дата погашения">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label>Имеются ли действующие кредиты, займы</label>
                        <select class="form-select"
                                required
                                name="has_credits"
                                id="has_credits">
                            <option value="0" @if(!$clientForm->has_credits) selected @endif>
                                нет
                            </option>
                            <option value="1" @if($clientForm->has_credits) selected @endif>
                                да
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Ежемесячный платеж</label>
                        <div class="input-group">
                            <input class="form-control"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   @if(!$clientForm->has_credits) readonly @endif
                                   name="monthly_payment"
                                   id="monthly_payment"
                                   placeholder="Ежемесячный платеж"
                                   value="{{old('monthly_payment', $clientForm->monthly_payment)}}">
                            <span class="input-group-text">руб.</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="block-section">
                <h4>Сведения о банкротстве</h4>
                <p align="center">Наличие производства по делу о несостоятельности (банкротстве) в течение 5 (пяти) лет
                    на дату подачи заявки на получение микрозайма</p>
                <div class="container">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="is_bankrupt"
                               id="bankruptTrue"
                               value="1"
                               @if($clientForm->is_bankrupt) checked @endif>
                        <label class="form-check-label" for="inlineRadio1">Да</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="is_bankrupt"
                               id="bankruptFalse"
                               value="0"
                               @if(!$clientForm->is_bankrupt) checked @endif>
                        <label class="form-check-label" for="inlineRadio2">Нет</label>
                    </div>
                    @if(!$clientForm->exists)
                        <a class="btn btn-warning"
                           target="_blank"
                           href="{{\App\Models\ClientForm::CHECK_CREDITS_LINK}}"
                           id="checkBankrupt">
                            Проверить
                        </a>
                    @endif
                </div>
            </div>

            <div class="block-section">
                <h4>Комментарий кассира</h4>
                <textarea class="form-control"
                          id="cashier_comment"
                          name="cashier_comment">
                    {{old('cashier_comment', $clientForm->cashier_comment)}}
                </textarea>
            </div>
        </form>

        <div class="modal fade" id="crud-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Новый клиент</h4>
                    </div>
                    <div class="modal-body">
                        <form name="addClientForm"
                              id="addClientForm"
                              action="{{route('client.store')}}"
                              method="POST">
                            @csrf
                            <input type="hidden" id="isJSON" name="isJSON" value="1">
                            <div class="form-group edit-fields">
                                <label for="surname">Фамилия</label>
                                <input class="form-control"
                                       type="text"
                                       name="surname"
                                       id="surname"
                                       placeholder="Фамилия"
                                       oninput="validate()">
                            </div>
                            <div class="form-group edit-fields">
                                <label for="name">Имя</label>
                                <input class="form-control"
                                       type="text"
                                       name="name"
                                       id="name"
                                       placeholder="Имя"
                                       oninput="validate()">
                            </div>
                            <div class="form-group edit-fields">
                                <label for="patronymic">Отчество</label>
                                <input class="form-control"
                                       type="text"
                                       name="patronymic"
                                       id="patronymic"
                                       placeholder="Отчество">
                            </div>
                            <div class="form-group edit-fields">
                                <label for="birt_dDate">Дата рождения</label>
                                <input class="form-control"
                                       type="date"
                                       name="birt_dDate"
                                       id="birt_dDate"
                                       placeholder="Дата рождения"
                                       oninput="validate()">
                            </div>
                            <div class="btn-group block-padding">
                                <button class="btn btn-primary"
                                        type="submit"
                                        id="btn-save"
                                        name="btnsave"
                                        disabled>
                                    Подтвердить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
