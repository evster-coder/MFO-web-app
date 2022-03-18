@extends('layouts.user')

@section('title')
    Заявки на выдачу займа
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clientform.css') }}">
    <script src="{{ asset('js/clientformsCRUD/table.js') }}" defer></script>
@endpush

@section('content')
    <div class="d-flex justify-content-between">
        <h1>Заявки на выдачу займа</h1>
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
                       data-export="{{route('clientForm.export')}}">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>
        @perm('create-clientform')
        <div class="add-clientform-btn">
            <a class="btn btn-primary"
               href="{{route('clientForm.create')}}"
               role="button">
                Добавить
            </a>
        </div>
        @endperm

        <table class="table clientform-table table-bordered mb-5">
            <thead>
            <tr class="table-info">
                <th scope="col" data-column-name="clientFormNumber">
                    <div class="form-group has-search">
                        <p align="center">Номер заявки</p>
                        <span class="fa fa-search form-control-feedback"></span>
                        <input class="form-control"
                               type="text"
                               name="searchClientFormNumber"
                               id="searchClientFormNumber"
                               placeholder="Поиск..."/>
                    </div>
                </th>
                <th scope="col" data-column-name="clientFormDate">
                    <div class="form-group has-search" style="width:13vw;">
                        <p align="center">Дата оформления</p>
                        <span class="fa fa-search form-control-feedback"></span>
                        <input class="form-control"
                               type="date"
                               name="searchClientFormDate"
                               id="searchClientFormDate"
                               placeholder="Поиск..."/>
                    </div>
                </th>
                <th scope="col" data-column-name="clientFIO">
                    <div class="form-group has-search">
                        <p align="center">Клиент</p>
                        <span class="fa fa-search form-control-feedback"></span>
                        <input class="form-control"
                               type="text"
                               name="searchClientFIO"
                               id="searchClientFIO"
                               placeholder="Поиск..."/>
                    </div>
                </th>
                <th scope="col">
                    <div class="form-group has-search">
                        <p align="center">Статус заявки</p>
                        <select name="searchState" id="searchState" class="form-select">
                            <option value="any">Любой</option>
                            <option value="considered">В рассмотрении</option>
                            <option value="accepted">Одобрена</option>
                            <option value="rejected">Отклонена</option>
                            <option value="loanSigned">Заключен договор</option>
                        </select>
                    </div>
                </th>
                <th scope="col"><p align="center">Действия</p></th>
            </tr>
            </thead>
            <tbody>
            <x-clientforms-tbody :clientForms="$clientForms"/>
            </tbody>
        </table>
        <input type="hidden" name="hiddenPage" id="hiddenPage" value="1"/>
    </div>
@endsection
