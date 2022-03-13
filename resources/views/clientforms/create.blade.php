@extends('layouts.user')

@section('title')
    @if($clientform->exists)
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
        @if($clientform->exists)
            Редактирование заявки на выдачу займа
        @else
            Создание заявки на выдачу займа
        @endif
    </h1>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form action="{{$clientform->exists ?
                            route('clientform.update', ['id' => $clientform->id]) : route('clientform.store')}}"
              method="POST">
            @if($clientform->exists)
                @method('PUT')
            @else
                @method('POST')
            @endif
            @csrf
            <div class="btn-group">
                <button class="btn btn-success"
                        type="submit"
                        id="saveClientform">
                    @if($clientform->exists)
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
                    <label for="orgunit_id">Клиент</label>
                    <select2 class="form-group"
                             required
                             data-width="100%"
                             :options="{{$clients}}"
                             name="client_id"
                             id="client_id"
                             value="{{old('client_id', $clientform->client_id)}}">
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
                               id="clientBirthDate"
                               placeholder="Дата рождения">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Мобильный телефон</label>
                        <input class="form-control"
                               type="text"
                               name="mobilePhone"
                               id="mobilePhone"
                               placeholder="Введите мобильный телефон"
                               value="{{old('mobilePhone', $clientform->mobilePhone)}}">
                    </div>
                    <div class="col">
                        <label>Домашний телефон</label>
                        <input class="form-control"
                               type="text"
                               name="homePhone"
                               id="homePhone"
                               placeholder="Введите домашний телефон"
                               value="{{old('homePhone', $clientform->homePhone)}}">
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
                               name="passportSeries" id="passportSeries"
                               placeholder="Серия"
                               value="{{old('passportSeries', $clientform->Passport ?
                                        $clientform->Passport->passportSeries : null )}}">
                    </div>
                    <div class="col">
                        <label>Номер</label>
                        <input class="form-control"
                               required
                               type="text"
                               name="passportNumber" id="passportNumber"
                               value="{{old('passportNumber', $clientform->Passport ?
                                        $clientform->Passport->passportNumber : null)}}"
                               placeholder="Номер">
                    </div>
                    <div class="col">
                        <label>Дата выдачи</label>
                        <input class="form-control"
                               required
                               type="date"
                               name="passportDateIssue"
                               id="passportDateIssue"
                               placeholder="Дата выдачи"
                               value="{{old('passportDateIssue', $clientform->Passport ?
                                        $clientform->Passport->passportDateIssue : null)}}">
                    </div>
                </div>

                <div class="form-group edit-fields">
                    <label>Кем выдан</label>
                    <textarea class="form-control"
                              required
                              id="passportIssuedBy"
                              name="passportIssuedBy">
                        {{old('passportIssuedBy', $clientform->Passport ?
                          $clientform->Passport->passportIssuedBy : null)}}
                    </textarea>
                </div>

                <div class="form-group edit-fields">
                    <label>Код подразделения</label>
                    <input class="form-control"
                           type="text"
                           name="passportDepartamentCode"
                           id="passportDepartamentCode"
                           value="{{old('passportDepartamentCode', $clientform->Passport ?
		            			    $clientform->Passport->passportDepartamentCode : null)}}"
                           placeholder="Введите код подразделения">
                </div>

                <div class="form-group edit-fields">
                    <label>Место рождения</label>
                    <textarea class="form-control"
                              id="passportBirthplace"
                              name="passportBirthplace">
                        {{old('passportBirthplace', $clientform->Passport ?
		            	$clientform->Passport->passportBirthplace : null)}}
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
                               value="{{old('snils', $clientform->snils)}}">
                    </div>
                    <div class="col">
                        <label>Пенсионное удостоверение №</label>
                        <input class="form-control"
                               type="text"
                               name="pensionerId"
                               id="pensionerId"
                               value="{{old('pensionerId', $clientform->pensionerId)}}"
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
                              name="passportResidenceAddress"
                              id="passportResidenceAddress">
                        {{old('passportResidenceAddress', $clientform->passportResidenceAddress)}}
                    </textarea>
                </div>

                <div class="form-group edit-fields">
                    <label>Фактически</label>
                    <textarea class="form-control"
                              required
                              id="actualResidenceAddress"
                              name="actualResidenceAddress">
                        {{old('actualResidenceAddress', $clientform->actualResidenceAddress)}}
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
                                name="maritalstatus_id"
                                id="maritalstatus_id">
                            @foreach($maritalstatuses as $maritalstatus)
                                <option @if ( $maritalstatus->id == $clientform->maritalstatus_id )
                                        selected
                                        @endif
                                        id="maritalstatus_id"
                                        name="maritalstatus_id"
                                        value=" {{ $maritalstatus->id }} ">
                                    {{$maritalstatus->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Количество детей</label>
                        <input class="form-control"
                               type="number"
                               name="numberOfDependents"
                               id="numberOfDependents"
                               placeholder="Количество иждевенцов"
                               value="{{old('numberOfDependents', $clientform->numberOfDependents)}}"
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
                           name="workPlaceName"
                           id="workPlaceName"
                           placeholder="Введите наименование организации"
                           value="{{old('workPlaceName', $clientform->workPlaceName)}}">
                </div>
                <div class="form-group edit-fields">
                    <label>Адрес работы</label>
                    <textarea class="form-control"
                              name="workPlaceAddress"
                              id="workPlaceAddress">
                        {{old('workPlaceAddress', $clientform->workPlaceAddress)}}
                    </textarea>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Должность</label>
                        <input class="form-control"
                               type="text"
                               name="workPlacePosition"
                               id="workPlacePosition"
                               value="{{old('workPlacePosition', $clientform->workPlacePosition)}}"
                               placeholder="Введите должность">
                    </div>
                    <div class="col">
                        <label>Стаж на месте работы</label>
                        <select class="form-select"
                                required
                                name="seniority_id"
                                id="seniority_id">
                            @foreach($seniorities as $seniority)
                                <option @if ( $seniority->id == $clientform->seniority_id )
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
                           name="workPlacePhone"
                           id="workPlacePhone"
                           placeholder="Введите рабочий телефон"
                           value="{{old('workPlacePhone', $clientform->workPlacePhone)}}">
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
                                   name="constainIncome"
                                   id="constainIncome"
                                   value="{{old('constainIncome', $clientform->constainIncome)}}">
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
                                   name="additionalIncome"
                                   id="additionalIncome"
                                   placeholder="Введите доп. доход"
                                   value="{{old('additionalIncome', $clientform->additionalIncome)}}">
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
                                   name="loanCost"
                                   id="loanCost"
                                   placeholder="Сумма займа"
                                   value="{{old('loanCost', $clientform->loanCost)}}">
                            <span class="input-group-text">руб.</span>
                        </div>
                    </div>
                    <div class="col">
                        <autocomplete-component
                            type="number"
                            required
                            rusname="Срок займа"
                            name="daysAmount"
                            path="{{route('loanterm.axiosList')}}"
                            id="loanTerm"
                            group-text="дней"
                            given-value="{{old('loanTerm', $clientform->loanTerm)}}"
                            selector="loanTerm"
                        />
                    </div>

                    <div class="col">
                        <autocomplete-component
                            type="number"
                            required
                            rusname="Процентная ставка"
                            name="percentValue"
                            path="{{route('interestrate.axiosList')}}"
                            id="interestRate"
                            group-text="%"
                            step="0.001"
                            given-value="{{old('interestRate', $clientform->interestRate)}}"
                        />

                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <label>Дата оформления</label>
                        <input class="form-control"
                               required
                               type="date"
                               name="loanDate"
                               id="loanDate"
                               placeholder="Введите дату оформления"
                               value="{{old('loanDate', $clientform->loanDate)}}">
                    </div>
                    <div class="col">
                        <label>Дата погашения</label>
                        <input class="form-control"
                               disabled
                               type="date"
                               id="loanMaturityDate"
                               name="loanMaturityDate"
                               placeholder="Дата погашения">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label>Имеются ли действующие кредиты, займы</label>
                        <select class="form-select"
                                required
                                name="hasCredits"
                                id="hasCredits">
                            <option value="0" @if(!$clientform->hasCredits) selected @endif>
                                нет
                            </option>
                            <option value="1" @if($clientform->hasCredits) selected @endif>
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
                                   @if(!$clientform->hasCredits) readonly @endif
                                   name="monthlyPayment"
                                   id="monthlyPayment"
                                   placeholder="Ежемесячный платеж"
                                   value="{{old('monthlyPayment', $clientform->monthlyPayment)}}">
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
                               name="isBankrupt"
                               id="bankruptTrue"
                               value="1"
                               @if($clientform->isBankrupt) checked @endif>
                        <label class="form-check-label" for="inlineRadio1">Да</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="radio"
                               name="isBankrupt"
                               id="bankruptFalse"
                               value="0"
                               @if(!$clientform->isBankrupt) checked @endif>
                        <label class="form-check-label" for="inlineRadio2">Нет</label>
                    </div>
                    @if(!$clientform->exists)
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
                          id="cashierComment"
                          name="cashierComment">
                    {{old('cashierComment', $clientform->cashierComment)}}
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
                                <label for="birthDate">Дата рождения</label>
                                <input class="form-control"
                                       type="date"
                                       name="birthDate"
                                       id="birthDate"
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
