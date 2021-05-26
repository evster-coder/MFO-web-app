
@extends('layouts.user')

@section('title')
	Договоры займов
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
    <script src="{{ asset('js/loansCRUD/table.js') }}" defer></script>
@endpush

@section('content')

		<h1>Договоры займов</h1>

		<div class="content-block">

			<x-auth-session-status class="mb-4" :status="session('status')" />
	    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

			<table class="table loan-table table-bordered mb-5">
				<thead>
					<tr class="table-info">

						<th scope="col" class="sorting" data-sorting-type="asc" 
														data-column-name="loanNumber">
				        	<div class="form-group has-search">
				        		<p align="center">Номер</p>
							    <span class="fa fa-search form-control-feedback"></span>
				      			<input type="text" name="searchLoanNumber" id="searchLoanNumber" class="form-control" placeholder="Поиск..." />
				      		</div>
						</th>
						<th scope="col" class="sorting" 
											data-sorting-type="asc" 
											data-column-name="loanConclusionDate">
				        	<div class="form-group has-search">
				        		<p align="center">Дата заключения</p>
							    <span class="fa fa-search form-control-feedback"></span>
				      			<input type="date" name="searchLoanConclusionDate" id="searchLoanConclusionDate" class="form-control" placeholder="Поиск..." />
				      		</div>
				      	</th>

						<th scope="col" data-column-name="clientName">
				        	<div class="form-group has-search">
				        		<p align="center">Клиент</p>
							    <span class="fa fa-search form-control-feedback"></span>
				      			<input type="text" name="searchClientFIO" id="searchClientFIO" class="form-control" placeholder="Поиск..." />
				      		</div>
						</th>
						<th scope="col" data-column-name="statusOpen">
				        	<div class="form-group has-search">
				        		<p align="center">Статус</p>
							    <span class="fa fa-search form-control-feedback"></span>
					        	<select name="searchStatusOpen" id="searchStatusOpen" class="form-select">
					        		<option value="">Любой</option>
					        		<option value="1">Открыт</option>
					        		<option value="0">Закрыт</option>
					        	</select>
						  	</div></th>
						<th scope="col" ><p align="center">Действия</p></th>
					</tr>
				</thead>
				<tbody>
					<x-loans-tbody :loans="$loans"></x-loans-tbody>
				</tbody>
			</table>

		    <input type="hidden" name="hiddenPage" id="hiddenPage" value="1" />
	    	<input type="hidden" name="hiddenSortColumn" id="hiddenSortColumn" value="loanNumber" />
	    	<input type="hidden" name="hiddenSortDesc" id="hiddenSortDesc" value="desc" />
    	</div>

@endsection