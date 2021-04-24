
@extends('layouts.user')

@section('title')
    Главная
@endsection

@section('content')

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        @if (Route::has('login'))
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log out') }}
                        </x-dropdown-link>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                @endauth
            </div>
        @endif


            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                @auth
                    @foreach (Auth::user()->roles as $role)
                        <p>{{$role->name }}</p>
                    @endforeach
                @else
                    <p>Не авторизован </p>
                @endauth
            </div>
    </div>
@endsection