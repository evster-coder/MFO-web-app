<div class="orgunit-manage">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Сменить подразделение
    </button>
    <p>Текущее : {{ session('OrgUnitCode') }}</p>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Смена текущего подразделения</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('orgunit.change') }}" id="formChange">
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

                    <button type="submit" class="btn btn-primary">Сменить</button>
                </form>
            </div>
        </div>
    </div>
</div>



