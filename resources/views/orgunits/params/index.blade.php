
@extends('layouts.user')

@section('title')
	Параметры подразделений
@endsection

@push('assets')
    <script src="{{ asset('js/params.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/datadicts.css') }}">
@endpush

@section('content')
	<h1>Список параметров подразделений</h1>
	<div class="content-block">

	   <x-auth-session-status class="mb-4" :status="session('status')" />
	   <x-auth-validation-errors class="mb-4" :errors="$errors" />

    	<div class="add-data-btn">
        @perm('create-orgunit-param')
      	<a class="btn btn-primary" href="javascript:void(0)" data-toggle="modal" id="new-data">Добавление</a>
        @endperm
          <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
              <input type="text" name="search" id="search" class="form-control" placeholder="Выполните поиск..." />
          </div>
    	</div>
    	<table class="table table-bordered data-table" id="data-table" 
                    data-url="{{route('param.list')}}">
          <thead>
          <tr class="table-primary">
            <th scope="col">Название</th>
            <th scope="col">Сокращение</th>
            <th scope="col">Тип данных</th>
            <th scope="col">Действия</th>
          </tr>
          </thead>
          <tbody>
            <x-orgunitparams-tbody :params="$params"></x-interestrates-tbody>
          </tbody>
    	</table>
      <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />


      <div class="modal fade" id="crud-modal" aria-hidden="true" >
        <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title" id="dataCrudModal"></h4>
          </div>
            <div class="modal-body">
              <form name="dataForm" id="dataForm" action="{{ route('param.store') }}" method="POST">
                <input type="hidden" name="dataId" id="dataId" >
                @csrf
                <div class="row">
                  <div class="form-group edit-fields">
                    <label for="name">Название</label>
                    <input type="text"
                              name="name" id="name" class="form-control" 
                              placeholder="Название" oninput="validate()" >
                  </div>

                  <div class="form-group edit-fields">
                    <label for="slug">Сокращение</label>
                    <input type="text"
                              name="slug" id="slug" class="form-control" 
                              placeholder="Сокращение" oninput="validate()" >
                  </div>

                  <div class="form-group edit-fields">
                    <label for="dataType">Тип данных</label>
                    <select name="dataType" id="dataType" type="text" class="form-select">
                        <option selected value="string">String</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                    </select>
                  </div>

                  <div class="btn-group block-padding">
                    <button type="submit" id="btn-save" name="btnsave" class="btn btn-primary" disabled>Подтвердить</button>
                    <a href="{{ route('param.index') }}" class="btn btn-danger">Отмена</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection