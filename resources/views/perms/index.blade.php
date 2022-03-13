@extends('layouts.user')

@section('title')
    Управление Правами
@endsection

@push('assets')
    <script src="{{ asset('js/permsCRUD/index.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/perms.css') }}">
@endpush

@section('content')
    <h1>Управление Правами</h1>
    <div class="content-block">
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <div class="add-perm-btn">
            <a class="btn btn-primary"
               href="javascript:void(0)"
               data-toggle="modal"
               id="new-perm">
                Добавление
            </a>
            <div class="form-group has-search">
                <span class="fa fa-search form-control-feedback"></span>
                <input class="form-control"
                       type="text"
                       name="search"
                       id="search"
                       placeholder="Выполните поиск..."/>
            </div>
        </div>

        <table class="table table-bordered perm-table">
            <thead>
            <tr class="table-primary">
                <th class="sorting"
                    scope="col"
                    data-sorting-type="asc"
                    data-column-name="name">
                    Название <span id="icon-name"></span>
                </th>
                <th class="sorting"
                    scope="col"
                    data-sorting-type="asc"
                    data-column-name="slug">
                    Сокращение <span id="icon-slug"></span>
                </th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            <x-perms-tbody :perms="$perms"></x-perms-tbody>
            </tbody>
        </table>

        <input type="hidden" name="hiddenPage" id="hiddenPage" value="1"/>
        <input type="hidden" name="hiddenSortColumn" id="hiddenSortColumn" value="name"/>
        <input type="hidden" name="hiddenSortDesc" id="hiddenSortDesc" value="desc"/>

        <div class="modal fade" id="crud-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="permCrudModal"></h4>
                    </div>
                    <div class="modal-body">
                        <form name="permForm" id="permForm" action="{{ route('perm.store') }}" method="POST">
                            <input type="hidden" name="permId" id="permId">
                            @csrf
                            <div class="row">
                                <div class="form-group edit-fields">
                                    <label for="name">Название</label>
                                    <input class="form-control"
                                           type="text"
                                           name="name"
                                           id="name"
                                           placeholder="Наименование"
                                           oninput="validate()">
                                </div>
                                <div class="form-group edit-fields">
                                    <label for="slug">Сокращение </label>
                                    <input class="form-control"
                                           type="text"
                                           name="slug"
                                           id="slug"
                                           placeholder="slug"
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
                                    <a class="btn btn-danger"
                                       href="{{ route('perm.index') }}">
                                        Отмена
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
