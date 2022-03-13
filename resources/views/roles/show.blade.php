@extends('layouts.user')

@php
    use App\Models\Role;
@endphp

@section('title')
    @if($role->exists)
        Роль - {{$role->name}}
    @else
        Новая роль
    @endif
@endsection

@push('assets')
    <script src="{{ asset('js/rolesCRUD/index.js') }}" defer></script>
@endpush

@section('content')

    <a class="btn btn-default" href="{{route('role.index')}}">< Назад</a>

    <h1>
        @if($role->exists)
            Роль {{$role->name}} ({{$role->slug}})
        @else
            Новая роль
        @endif
    </h1>

    <x-auth-session-status class="mb-4" :status="session('status')"/>
    <x-auth-validation-errors class="mb-4" :errors="$errors"/>

    <div class="content-block">
        @if($role->exists && $role->slug != Role::ADMIN_ROLE)
            <form method="POST" id="deleteForm" action="{{route('role.destroy', [$role->id])}}">
                @method('DELETE')
                @csrf
                <button type="submit"
                        hidden
                        onclick="return confirm('Вы действительно хотите удалить запись?');">
                </button>
            </form>
        @endif
        <form method="POST" action="{{$role->exists ? route('role.update', [$role->id]) : route('role.store')}}">
            @csrf
            @if ($role->exists)
                @method('PUT')
            @else
                @method('POST')
            @endif
            <div class="btn-group block-padding">
                <button type="submit" class="btn btn-success">
                    @if($role->exists)
                        Обновить
                    @else
                        Сохранить
                    @endif
                </button>

                @if($role->exists && $role->slug != Role::ADMIN_ROLE)
                    <a class="btn btn-danger erase-role"
                       href=""
                       data-id="{{ $role->id }}">
                        Удалить
                    </a>
                @endif
            </div>

            @if (!$role->exists)
                <div class="block-section">
                    <h4>Основное</h4><br>
                    <div class="form-group edit-fields">
                        <label for="name">Название</label>
                        <input class="form-control"
                               required
                               name="name"
                               id="name"
                               type="text"
                               placeholder="Введите название"
                               value="{{ old( 'name', $role->name) }}">
                    </div>
                    <div class="form-group edit-fields">
                        <label for="slug">Сокращение (slug)</label>
                        <input class="form-control"
                               required
                               name="slug"
                               id="slug"
                               type="text"
                               placeholder="Введите slug"
                               value="{{ old( 'slug', $role->slug) }}">
                    </div>
                </div>
            @endif

            <div class="block-section">
                <h4>Права</h4><br>
                <table class="table table-light table-hover">
                    <thead>
                    <tr class="table-primary">
                        <th scope="col">Название права</th>
                        <th scope="col">Можно</th>
                        <th scope="col">Нельзя</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $perm)
                        <tr>
                            @php
                                $checked = $role->exists && $role->contains($perm->slug);
                                $isAdmin = ($role->exists && $role->slug == Role::ADMIN_ROLE);
                            @endphp
                            <td><strong> {{$perm->name}} ({{$perm->slug}})</strong></td>
                            <td align="center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="perm[{{$perm->id}}]"
                                           id="{{$perm->id}}-y"
                                           value="1"
                                           @if($checked) checked @endif
                                           @if($isAdmin) disabled @endif >
                                </div>
                            </td>
                            <td align="center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="perm[{{$perm->id}}]"
                                           id="{{$perm->id}}_n"
                                           value="0"
                                           @if(!$checked) checked @endif
                                           @if($isAdmin) disabled @endif >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
@endsection
