<ul class="navbar-nav" id="orgUnitsList">
    @php
    function buildList($items, $parent)
    {
        foreach ($items as $item) {
            if (isset($item->childOrgUnits)) {
            @endphp
                <li class="nav-item">
                        {{ $item->orgUnitCode }}
                    </a>
                    <ul>
                        @php  buildList($item->childOrgUnits, 'subnav-'.$item->id) @endphp
                    </ul>
                </li>
            @php
            } else {
            @endphp
                <li class="nav-item">
                    <a href="{{ $item->url }}" class="nav-link">{{ $item->orgUnitCode }}</a>
                </li>
            @php
            }
        }
    }

    buildList($orgUnits, 'mainMenu')
    @endphp
</ul>