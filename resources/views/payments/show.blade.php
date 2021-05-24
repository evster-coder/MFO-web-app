  @extends('layouts.user')

@section('title')
	Платеж от 30.05.2020 по договору займа №12321 от 29.05.2020
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
	<a href="{{route('loan.index')}}" class="btn btn-default">< К договору займа</a>
	<h1>Подробности платежа</h1>

	<div class="block-content">

		@role('cashier')
		<div class="block-padding d-flex">
          	<a class="btn btn-info" href="{{route('payment.create') }}" role="button">
            	Редактировать
          	</a>

            <form method="POST" action="">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-info" onclick="return confirm('Вы действительно хотите удалить запись?');">Удалить</button>
            </form>
        </div>
        @endrole

		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

    	<div class="block-section">
    		<h4>Платеж</h4>
        <table class="table">
          <tbody>
            <tr>
              <td>Договор</td>
              <td><a href="{{route('loan.show')}}"><p>Договор №123321 от 29.05.2020 <i class="fas fa-external-link-alt"></i></p></a>
              </td>
            </tr>
            <tr>
              <td>Подразделение</td>
              <td>S-121</td>
            </tr>
            <tr>
              <td>Клиент</td>
              <td><a href=""><p>Петров Иван Иванович <i class="fas fa-external-link-alt"></i></p></a>
              </td>
            </tr>
            <tr>
              <td>Сумма платежа</td>
              <td>5000 руб.</td>
            </tr>
            <tr>
              <td>Дата поступления платежа</td>
              <td>30.05.2020</td>
            </tr>
            <tr>
              <td>Пользователь</td>
              <td>Cashier D.Y.</td>
            </tr>
          </tbody>
        </table>
		</div>
	</div>
	     
@endsection