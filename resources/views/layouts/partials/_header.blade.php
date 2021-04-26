<x-select-orgunit :orgUnits=$orgUnits />

<ul>
    @perm('manage-users')
        <li class="nav-item">
            <a href="{{route('user.index')}}" class="nav-link">Пользователи</a>
        </li>
    @endperm

    @perm('manage-orgunits')
        <li class="nav-item">
            Подразделения
        </li>
    @endperm
    
</ul>