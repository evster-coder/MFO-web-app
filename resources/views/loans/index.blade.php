
@extends('layouts.user')

@section('title')
	Договоры займов
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')

		<h1>Договоры займов</h1>

		<div class="content-block">

			<x-auth-session-status class="mb-4" :status="session('status')" />
	    	<x-auth-validation-errors class="mb-4" :errors="$errors" />


	      	<div class="add-client-btn">
      		@role('cashier-user')
	        	<a class="btn btn-primary" href="{{ route('user.create') }}" role="button">Добавить</a>
	      	@endrole
	      	</div>

			<table class="table client-table table-bordered mb-5">
				<thead>
					<tr class="table-info">

						<th scope="col" class="sorting" data-sorting-type="asc" 
														data-column-name="loanNumber">
				        	<div class="form-group has-search">
				        		<p align="center">Номер</p>
							    <span class="fa fa-search form-control-feedback"></span>
				      			<input type="text" name="search" id="search" class="form-control" placeholder="Поиск..." />
				      		</div>
						</th>
						<th scope="col" class="sorting" 
											data-sorting-type="asc" 
											data-column-name="loanDate">
				        	<div class="form-group has-search">
				        		<p align="center">Дата</p>
							    <span class="fa fa-search form-control-feedback"></span>
				      			<input type="date" name="search" id="search" class="form-control" placeholder="Поиск..." />
				      		</div>
				      	</th>

						<th scope="col" class="sorting" data-sorting-type="asc" 
														data-column-name="clientName">
				        	<div class="form-group has-search">
				        		<p align="center">Клиент</p>
							    <span class="fa fa-search form-control-feedback"></span>
				      			<input type="text" name="search" id="search" class="form-control" placeholder="Поиск..." />
				      		</div>
						</th>
						<th scope="col" class="sorting">
				        	<div class="form-group has-search">
				        		<p align="center">Статус</p>
							    <span class="fa fa-search form-control-feedback"></span>
				      			<input type="text" name="search" id="search" class="form-control" placeholder="Поиск..." />
						  	</div></th>
						<th scope="col" ><p align="center">Действия</p></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>123321</td>
						<td>29.05.2020</td>
						<td>Петров Иван Иванович</td>
						<td>Действующий</td>
						<td>
						<div class = "d-flex manage-btns">
			                <!-- Админские кнопки редактирования и удаления -->
			                <a class="btn btn-success" href="{{route('loan.show')}}" role="button">
			                	<i class="fas fa-eye"></i>
			        		</a>

			                <form method="POST" action="">
			                  @method('DELETE')
			                  @csrf
			                  <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить запись?');"><i class="fas fa-trash-alt"></i></button>
			                </form>

    					</div>
						</td>
					</tr>
				</tbody>
			</table>

		    <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
    	</div>

@endsection