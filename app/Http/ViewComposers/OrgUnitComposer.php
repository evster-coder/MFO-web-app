<?php

namespace App\Http\ViewComposers;

use App\Models\OrgUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrgUnitComposer
{
    public function compose(View $view)
    {
        $childs = OrgUnit::find(Auth::user()->orgunit_id)->with('getSubtree')->get();
        $subtree = OrgUnit::where('id', Auth::user()->orgunit_id)
                        ->get()
                        ->merge($childs);

        $subtree = $this->buildTree($subtree);

        return $view->with('orgUnits', $subtree);
    }

    public function buildTree($items)
    {
        $grouped = $items->groupBy('orgunit_id');

        foreach ($items as $item) {
            if ($grouped->has($item->id)) {
                $item->childOrgUnits = $grouped[$item->id];
            }
        }
        return $items->where('id', Auth::user()->orgunit_id);
    }
}