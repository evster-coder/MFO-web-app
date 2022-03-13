@extends('layouts.guest')

@section('title')
    Авторизация в системе
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="login-block col-md-7">
                <h2>Вход в систему</h2>
                <div class="login-form>">
                    <x-auth-validation-errors class="mb-4" :errors="$errors"/>

                    <form class="login-form"
                          method="POST"
                          action="{{ route('login') }}">
                        @csrf

                        <div class="font-medium text-sm text-success">
                            {{ session('status') }}
                        </div>

                        <div class="form-group">
                            <label for="username" class="form-label"> Логин </label>
                            <input class="form-control"
                                   id="username"
                                   type="text"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="Логин"
                                   required
                                   autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label"> Пароль </label>
                            <input class="form-control"
                                   type="password"
                                   id="password"
                                   name="password"
                                   placeholder="Пароль">
                        </div>
                        <div class="block-remember-me">
                            <label for="remember_me" class="form-label">
                                <input
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember">
                                <span>Запомнить</span>
                            </label>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary"
                                    type="submit">
                                Войти
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
