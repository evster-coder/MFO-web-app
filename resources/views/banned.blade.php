@extends('layouts.user')

@section('title')
    ПОЛЬЗОВАТЕЛЬ ЗАБЛОКИРОВАН!
@endsection

@push('assets')
@endpush

@section('content')

    <h1 align="center">Пользователь заблокирован</h1>

    <div class="content-block">

        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <div class="block-section">
            <form method="POST" class="row" action="{{ route('logout') }}">
                @csrf
                <p align="center"> Пользователь заблокирован, доступ запрещен!</p>
                <div class="col text-center">
                    <button type="submit" class="btn btn-danger">Выход из системы</button>
                </div>
            </form>

        </div>
    </div>
@endsection
