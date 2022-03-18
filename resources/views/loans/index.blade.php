@extends('layouts.user')

@section('title')
    Договоры займов
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/loans.css') }}">
    <script src="{{ asset('js/loansCRUD/table.js') }}" defer></script>
@endpush

@section('content')
    <div class="d-flex justify-content-between">
        <h1>Договоры займов</h1>
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
                       data-export="{{route('loan.export')}}">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <table class="table loan-table table-bordered mb-5">
            <thead>
            <tr class="table-info">
                <th scope="col">
                    <div class="form-group has-search">
                        <p class="sorting"
                           align="center"
                           data-sorting-type="asc"
                           data-column-name="loan_number">
                            Номер <span id="icon-loanNumber"></span>
                        </p>
                        <span class="fa fa-search form-control-feedback"></span>
                        <input class="form-control"
                               type="text"
                               name="searchLoanNumber"
                               id="searchLoanNumber"
                               placeholder="Поиск..."/>
                    </div>
                </th>
                <th scope="col">
                    <div class="form-group has-search">
                        <p class="sorting"
                           align="center"
                           data-sorting-type="asc"
                           data-column-name="loan_conclusion_date">
                            Дата заключения <span id="icon-loanConclusionDate"></span>
                        </p>
                        <span class="fa fa-search form-control-feedback"></span>
                        <input class="form-control"
                               type="date"
                               name="searchLoanConclusionDate"
                               id="searchLoanConclusionDate"
                               placeholder="Поиск..."/>
                    </div>
                </th>

                <th scope="col" data-column-name="clientName">
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
                <th scope="col" data-column-name="status_open">
                    <div class="form-group has-search">
                        <p align="center">Статус</p>
                        <span class="fa fa-search form-control-feedback"></span>
                        <select class="form-select"
                                name="searchStatusOpen"
                                id="searchStatusOpen">
                            <option value="">Любой</option>
                            <option value="1">Открыт</option>
                            <option value="0">Закрыт</option>
                        </select>
                    </div>
                </th>
                <th scope="col"><p align="center">Действия</p></th>
            </tr>
            </thead>
            <tbody>
            <x-loans-tbody :loans="$loans"/>
            </tbody>
        </table>

        <input type="hidden" name="hiddenPage" id="hiddenPage" value="1"/>
        <input type="hidden" name="hiddenSortColumn" id="hiddenSortColumn" value="loan_number"/>
        <input type="hidden" name="hiddenSortDesc" id="hiddenSortDesc" value="desc"/>
    </div>
@endsection
