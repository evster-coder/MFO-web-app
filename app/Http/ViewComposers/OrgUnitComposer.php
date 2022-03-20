<?php

namespace App\Http\ViewComposers;

use App\Models\OrgUnit;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrgUnitComposer
{
    /**
     * @param View $view
     * @return View
     */
    public function compose(View $view): View
    {
        $subtree = OrgUnit::whereDescendantOrSelf(Auth::user()->orgUnit)->get();
        $subtree = $this->buildTree($subtree);

        return $view->with('orgUnits', $subtree);
    }

    /**
     * @param $items
     * @return mixed
     */
    public function buildTree($items)
    {
        $grouped = $items->groupBy('parent_id');

        foreach ($items as $item) {
            if ($grouped->has($item->id)) {
                $item->childOrgUnits = $grouped[$item->id];
            }
        }
        return $items->where('id', Auth::user()->org_unit_id);
    }
}
