@extends('layouts.user')

@section('title')
    История одобрений
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
    <script src="{{ asset('js/clientformsCRUD/approvals/table.js') }}" defer></script>
@endpush

@section('content')
    <div class="d-flex justify-content-between">
        <h1>История одобрений директора</h1>
        <div class="dropdown" style="margin-top:auto; margin-bottom: auto;">
            <a class="btn btn-secondary dropdown-toggle"
               href="#" role="button"
               id="dropdownMenuLink"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                Экспорт
            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <li>
                    <a class="dropdown-item"
                       id="exportExcelDirector"
                       data-export="{{route('directorApproval.export')}}">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>
        <div class="d-flex block-padding">
            <div class="form-group has-search px-3">
                <strong>от</strong>
                <span class="fa fa-search form-control-feedback"></span>
                <input class="form-control"
                       type="date"
                       id="searchFrom"
                       placeholder="От..."/>
            </div>
            <div class="form-group has-search">
                <strong>до</strong>
                <span class="fa fa-search form-control-feedback"></span>
                <input class="form-control"
                       type="date"
                       id="searchTo"
                       placeholder="До..."/>
            </div>
        </div>


        <table class="table mb-5"
               id="directorTable"
               data-url="{{route('directorApproval.list')}}">
            <thead>
            <tr class="table-info">
                <th scope="col">№ заявки</th>
                <th scope="col">Дата одобрения</th>
                <th scope="col">Клиент</th>
                <th scope="col">Сумма займа</th>
                <th scope="col">Пользователь</th>
                <th scope="col">Статус</th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            <x-director-approvals-tbody :clientforms="$clientforms"/>
            </tbody>
        </table>
        <input type="hidden" name="hiddenPage" id="hiddenPage" value="1"/>
    </div>
@endsection
