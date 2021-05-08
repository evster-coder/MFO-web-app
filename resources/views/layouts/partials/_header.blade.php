<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">

        <a href="{{url('/')}}" class="navbar-brand">Система МФО</a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navContent" aria-controls="#navContent" aria-expanded="false" aria-label="Toggle navigation"><span><i class="fas fa-bars"></i></span></button>

        <div id="navContent" class="collapse navbar-collapse">

        <!-- Горизонтальная часть меню -->
        <div class="navbar-nav ms-auto">
            @auth
                <form method="POST" class="row" action="{{ route('logout') }}">
                    @csrf
                    <div class="col">
                        <a id="userLink" class="nav-link nav-item" href="#">
                        {{ Auth::user()->username }}
                        </a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn nav-item">Выход</button>
                    </div>
                </form>

                <x-select-orgunit :orgUnits=$orgUnits />

            @endauth
        </div>

        <!-- Вертикальная часть меню -->
        <div class="navbar-nav">
            <ul class="navbar-nav me-auto sidenav">
                <li class="nav-item">
                    <a href="{{url('/')}}" class="nav-link">Главная</a>
                </li>

                @role('admin')
                    <li class="nav-item has-sub">
                        <a class="nav-link" id="users" role="button" aria-expanded="false">
                            Пользователи и права
                            <i class="fas fa-sort-down"></i>
                        </a>

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="{{route('user.index')}}" class="nav-link">
                                Пользователи
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('role.index')}}">
                                Роли
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('perm.index')}}">
                                Права
                                </a>
                            </li>
                        </ul>

                    </li>
                @else
                    @perm('view-users')
                        <li class="nav-item">
                            <a href="{{route('user.index')}}" class="nav-link">Пользователи</a>
                        </li>
                    @endperm

                @endrole

                @perm('view-orgunits')
                    <li class="nav-item has-sub">
                        <a class="nav-link" id="dictionarities" role="button" aria-expanded="false">
                            Структура организации
                            <i class="fas fa-sort-down"></i>
                        </a>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="{{route('orgunit.index')}}" class="nav-link">
                                Подразделения
                                </a>
                            </li>
                            @perm('view-orgunits-param')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('orgunit.index')}}">
                                    Параметры подразделений
                                    </a>
                                </li>
                            @endperm
                        </ul>

                    </li>
                @endperm

                @perm('view-dictionaries')

                @endperm

                @perm('manage-datadicts')
                    <li class="nav-item has-sub">


                        <a class="nav-link" id="dictionarities" role="button" aria-expanded="false">
                            Справочные поля
                            <i class="fas fa-sort-down"></i>
                        </a>

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('interestrate.index')}}">
                                Процентная ставка
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('maritalstatus.index')}}">
                                Семейное положение
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('loanterm.index')}}">
                                Сроки займа
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('seniority.index')}}">
                                Стаж на месте работы
                                </a>
                            </li>
                        </ul>

                    </li>
                @endperm
            </ul>
        </div>
    </div>
</nav>