  
@extends('layouts.user')

@section('title')
	Подразделение {{$orgunit->orgUnitCode}}
@endsection

@push('assets')
@endpush

@section('content')
    <a href="{{route('orgunit.index')}}" class="btn btn-default">< К списку</a>

	<h1> Подразделение {{$orgunit->orgUnitCode}}</h1>

	<div class="block-content">

		<div class="block-padding d-flex">
			@perm('edit-orgunit')
          	<a class="btn btn-info" href="{{route('orgunit.edit', [$orgunit->id]) }}" role="button">
            	Редактировать
          	</a>

 			@endperm

			@perm('delete-orgunit')
            <form method="POST" action="{{route('orgunit.destroy', [$orgunit->id]) }}">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить подразделение?');">Удалить</button>
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
				<a class="nav-link" data-bs-toggle="tab" href="#tabs-2" role="tab">Параметры подразделения</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="tab" href="#tabs-3" role="tab">Пользователи</a>
			</li>
		</ul><!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="tabs-1" role="tabpanel">
				<h5>Сведения: </h5><br>
				<p>Код: {{$orgunit->orgUnitCode}}</p>
				<p>Справочники: 
					@if($orgunit->hasDictionaries)
						Разрешены
					@else
						Запрещены
					@endif
				</p>
				<p>Родительское подразделение: 
					@if($orgunit->parent)
						<a href="{{route('orgunit.show', [$orgunit->parent->id])}}" class="btn btn-link">	{{$orgunit->parent->orgUnitCode}}
                    		<i class="fas fa-external-link-alt"></i>
						</a>
					@else
						нет
					@endif
				</p>
				<p>Дочерние подразделения:
					@if(count($orgunit->children) == 0)
					нет
					@else
					<ul>
						@foreach ($orgunit->children as $child)
							<li> 					
								<a href="{{route('orgunit.show', [$child->id])}}" class="btn btn-link">
                                	{{$child->orgUnitCode}}
                                	<i class="fas fa-external-link-alt"></i>
								</a>
							</li>
						@endforeach
					</ul>
					@endif
				</p>
			</div>
			<div class="tab-pane" id="tabs-2" role="tabpanel">
				<h5>Значения параметров: </h5><br>
				<table class="table mb-5 table-hover">
					<thead>
						<tr>
							<th>Параметр</th>
							<th>Значение</th>
							<th>Источник параметра</th>
						</tr>
					</thead>
					<tbody>
						@foreach($params as $param)
						<tr>
							@if($param->OrgUnitParam)
								<td>{{$param->OrgUnitParam->name}}</td>
								<td>
									@if($param->OrgUnitParam->dataType == 'date')
										{{$param->dataAsDate}}
									@elseif($param->OrgUnitParam->dataType == 'number')
										{{$param->dataAsNumber}}
									@elseif($param->OrgUnitParam->dataType == 'string')
										{{$param->dataAsString}}
									@else
									- Не установлено
									@endif
								</td>
								<td align="right">
									{{$param->OrgUnit->orgUnitCode}}
								</td>
							@else
							<td>{{$param->name}}</td>
							<td>Не установлено</td> 
							<td>-</td>
							@endif
						<tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="tabs-3" role="tabpanel">
				<h5>Пользователи в подразделении:</h5><br>
				<table class="table mb-5">
					<thead>
						<tr>
							<th>Пользователь</th>
							<th>Статус</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
							<tr>
								<td>
									<a href="{{route('user.show', [$user->id])}}">
									{{$user->username}} ({{$user->FIO}})
									</a>
								</td>
								<td>					
								@if ($user->blocked)
									<span class="badge bg-danger">Заблокирован</span>
								@else
									<span class="badge bg-success">Активен</span>
								@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
@endsection
