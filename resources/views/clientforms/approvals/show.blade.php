  
@extends('layouts.user')

@section('title')
	Проверка заявки на займ
@endsection

@push('assets')
    <!--<link rel="stylesheet" href="{{ asset('css/clients.css') }}">-->
@endpush

@section('content')
	<a href="{{route('clientform.approval-list')}}" class="btn btn-default">< К списку</a>

	<h1>Согласование заявки на займ №123</h1>
	<div class="content-block">
		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

    	<div class="block-padding">
    		<a href="" class="btn btn-primary">
    			Одобрить
    		</a>
    		<a href="" class="btn btn-warning">
    			Отклонить
    		</a>
    	</div>

    	<div class="block-section">
    		<h4>Клиент</h4>
    		<a href="{{route('client.show')}}" class="btn btn-info">
    			Проверка клиента
    		</a>
		  	<hr>
    		<p>Фамилия: Петров</p>
    		<p>Имя: Иван</p>
    		<p>Отчество: Иванович</p>
    		<p>Дата рождения: 20.08.1999</p>

    	</div>
    	<div class="block-section">
    		<h4>Анкета</h4>
    		<a href="{{route('clientform.show')}}" class="btn btn-info">
    			Проверка анкеты
    		</a>
    		<hr>
    		<p>Дата оформления: 29.05.2020</p>
    		<p>Сумма займа: 550000 руб.</p>
    		<p>Срок займа: 15 дней</p>
    	</div>

	</div>
@endsection