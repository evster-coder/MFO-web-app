
@extends('layouts.user')

@section('title')
	Справочник: Семейное положение
@endsection

@push('assets')
    <script src="{{ asset('js/datadictsCRUD/index.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/datadicts.css') }}">
@endpush

@section('content')
	<h1>Справочник: Семейное положение</h1>
	<div class="content-block">

		<x-auth-session-status class="mb-4" :status="session('status')" />
  	<x-auth-validation-errors class="mb-4" :errors="$errors" />

      	<div class="add-data-btn">
        	<a class="btn btn-primary" href="javascript:void(0)" data-toggle="modal" id="new-data">Добавление</a>
            <div class="form-group has-search">
              <span class="fa fa-search form-control-feedback"></span>
                <input type="text" name="search" id="search" class="form-control" placeholder="Выполните поиск..." />
            </div>
      	</div>
      	<table class="table table-bordered data-table" id="data-table" data-url="/maritalstatuses/get-statuses">
            <thead>
            <tr class="table-primary">
              <th scope="col">Значение параметра</th>
              <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
              <x-maritalstatuses-tbody :statuses="$statuses"></x-maritalstatuses-tbody>
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
                <form name="dataForm" action="{{ route('maritalstatus.store') }}" method="POST">
                  <input type="hidden" name="dataId" id="dataId" >
                  @csrf
                  <div class="row">
                    <div class="form-group edit-fields">
                      <label for="name">Название</label>
                      <input type="text" name="name" id="form-data" class="form-control" placeholder="Наименование" oninput="validate()" >
                    </div>
                    <div class="btn-group block-padding">
                      <button type="submit" id="btn-save" name="btnsave" class="btn btn-primary" disabled>Подтвердить</button>
                      <a href="{{ route('maritalstatus.index') }}" class="btn btn-danger">Отмена</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection

<script>
  function validate()
  {
    if(document.dataForm.name.value !='')
      document.dataForm.btnsave.disabled=false
    else
      document.dataForm.btnsave.disabled=true
  }
</script>
