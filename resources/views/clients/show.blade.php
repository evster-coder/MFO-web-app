@extends('layouts.user')

@section('title')
	Клиент {{$client->surname}} {{$client->name}} {{$client->patronymic}}
@endsection

@push('assets')
    <link rel="stylesheet" href="{{ asset('css/clients.css') }}">
@endpush

@section('content')
	<a href="{{route('client.index')}}" class="btn btn-default">< К списку</a>
	<h1>Клиент {{$client->surname}} {{$client->name}} {{$client->patronymic}}</h1>

	<div class="block-content">

		<div class="block-padding d-flex">
			@perm('edit-client')
          	<a class="btn btn-info" href="{{route('client.edit', [$client->id]) }}" role="button">
            	Редактировать
          	</a>
          	@endperm

          	@perm('delete-client')
            <form method="POST" action="">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-info" onclick="return confirm('Вы действительно хотите удалить запись?');">Удалить</button>
            </form>
            @endperm
        </div>

		<x-auth-session-status class="mb-4" :status="session('status')" />
    	<x-auth-validation-errors class="mb-4" :errors="$errors" />

		<ul class="nav nav-tabs" id="blockinfo" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-bs-toggle="tab" href="#tabs-1" role="tab">Основное</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#tabs-2" role="tab">Анкеты</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#tabs-3" role="tab">Договоры займов</a>
			</li>
		</ul><!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="tabs-1" role="tabpanel">
	    		<h4>Основное</h4><br>
	    		<table class="table">
	    			<tbody>
	    				<tr> 
	    					<td>Фамилия</td>
	    					<td>{{$client->surname}}</td>
	    				</tr>
	    				<tr> 
	    					<td>Имя</td>
	    					<td>{{$client->name}}</td>
	    				</tr>
	    				<tr> 
	    					<td>Отчество</td>
	    					<td>{{$client->patronymic}}</td>
	    				</tr>
	    				<tr> 
	    					<td>Дата рождения</td>
	    					<td>{{date("d-m-Y", strtotime($client->birthDate))}}</td>
	    				</tr>

	    				<tr> 
	    					<td>Подразделение</td>
	    					<td>{{$client->OrgUnit->orgUnitCode}}</td>
	    				</tr>
	    			</tbody>
		    	</table>

			</div>
			<div class="tab-pane" id="tabs-2" role="tabpanel">
	    		<h4>Анкеты</h4>
	    		<div class="d-flex block-padding">
		            <div class="form-group has-search px-3">
	           			<strong>от</strong>
		              <span class="fa fa-search form-control-feedback"></span>
		                <input type="date" name="search" id="search" class="form-control" placeholder="От..." />
		            </div>
	           		<div class="form-group has-search">
	           			<strong>до</strong>
		              <span class="fa fa-search form-control-feedback"></span>
		                <input type="date" name="search" id="search" class="form-control" placeholder="До..." />
		            </div>
	       	 	</div>

		    	<table class="table table-light table-hover">
		    		<thead>
						<tr class="table-primary">
							<th scope="col">Дата</th>
						</tr>
		    		</thead>
		    		<tbody>
				    	@foreach($clientforms as $clientform)
			    		<tr>
				    		<td>
				    			<a href="{{route('clientform.show', ['id' => $clientform->id])}}" class="btn btn-link"><strong>Анкета №{{$clientform->id}} от {{$clientform->loanDate}}</strong>
				    			<i class="fas fa-external-link-alt"></i>
			    				</a>
			    			</td>
						</tr>
				    	@endforeach
			    	</tbody>
				</table>

	    		<div>
				</div>
			</div>
			<div class="tab-pane" id="tabs-3" role="tabpanel">
				<h4>Договоры займов</h4>
	    		<div class="d-flex block-padding">
		            <div class="form-group has-search px-3">
	           			<strong>№ договора</strong>
		              <span class="fa fa-search form-control-feedback"></span>
		                <input type="text" name="search" id="search" class="form-control" placeholder="№ договора..." />
		            </div>
	           		<div class="form-group has-search">
	           			<strong>от </strong>
		              <span class="fa fa-search form-control-feedback"></span>
		                <input type="date" name="search" id="search" class="form-control" placeholder="Дата..." />
		            </div>
	       	 	</div>
		    	<table class="table table-light table-hover">
		    		<thead>
						<tr class="table-primary">
							<th scope="col">Номер договора</th>
						</tr>
		    		</thead>
		    		<tbody>
			    		<tr>
				    		<td>
				    			<a href="" class="btn btn-link"><strong>
					    			Договор № 382932712A от 29.05.2020
				    			</strong>
				    			<i class="fas fa-external-link-alt"></i>
			    				</a>
			    			</td>
						</tr>
						</a>
			    	</tbody>
				</table>
			</div>

		</div>
	</div>
	     
@endsection