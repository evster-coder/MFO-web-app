
@extends('layouts.guest')

@section('title')
    Авторизация в системе
@endsection

@section('content')

    <div class="login-block">
        <h2>Вход в систему</h2>
        <div class="login-form>">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" class="login-form" action="{{ route('login') }}">
                @csrf

                <div class="font-medium text-sm text-success">
                    {{ session('status') }}
                </div>

                <div class="form-group">
                    <label for="username" class="form-label"> Логин </label>
                    <input id="username" class="form-control" type="text" name="username" value="{{ old('username') }}"
                        placeholder="Логин" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label"> Пароль </label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Пароль">
                </div>

                <!-- Remember Me -->
                <div class="block-remember-me">
                    <label for="remember_me" class="form-label">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span>Запомнить</span>
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Войти</button>
                </div>
            </form>
        </div>
    </div>

@endsection