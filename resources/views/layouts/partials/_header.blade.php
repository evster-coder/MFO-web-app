 <form method="POST" action="{{ route('orgunits.change') }}" id="formChange">
    @method('PUT')
    @csrf

<ul id="orgUnitsList">
    @php

    function buildList($items, $parent)
    {
        foreach ($items as $item) {

            @endphp
                <div>
                    @if (isset($item->childOrgUnits))
                       <strong class="expandIcon">+</strong>
                    @else
                        <strong class="space"></strong>
                    @endif

                    <li class="orgUnitItem expanded">
                        <label>
                        <span class="badge bg-warning text-dark">
                        <input type="radio" name="orgUnit" value="{{ $item->id }}"
                            @if (session('OrgUnit') == $item->id)
                                checked
                            @endif
                        > 

                            {{ $item->orgUnitCode }}
                        </span>
                        </label>

                        @if (isset($item->childOrgUnits))
                            <ul>
                            @php  
                                buildList($item->childOrgUnits, 'subunit-'.$item->id) 
                            @endphp
                            </ul>
                        @endif

                    </li>
                </div>
            @php
        }
    }

    buildList($orgUnits, 'mainMenu')
    @endphp
</ul>

    <button type="submit" class="btn btn-info">Сменить</button>
</form>