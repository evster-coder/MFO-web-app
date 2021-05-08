<ul>
@foreach($childs as $child)
	<div class="d-flex">

	<li class="expanded-orgunit single-unit">
	@if($child->childOrgUnits)
   		<i class="can-expand fas fa-search-minus"></i>
    @else
    	<i class="fas fa-book"></i>
    @endif
    <strong data-value="{{$child->id}}">{{$child->orgUnitCode}}</strong>
	@if($child->childOrgUnits)
		<x-manage-child-tree :childs="$child->childOrgUnits"></x-manage-child-tree>
        @endif
	</li>
</div>
@endforeach
</ul>