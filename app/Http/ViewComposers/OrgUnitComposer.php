<?php

namespace App\Http\ViewComposers;

use App\Models\OrgUnit;
use Illuminate\View\View;

class OrgUnitComposer
{
    public function compose(View $view)
    {
        $orgUnitItems = OrgUnit::whereNotNull('hasDictionaries')
            ->ofSort(['orgunit_id' => 'asc'])
            ->get();

        $orgUnitItems = $this->buildTree($orgUnitItems);

        return $view->with('orgUnits', $orgUnitItems);
    }

    public function buildTree($items)
    {
        $grouped = $items->groupBy('orgunit_id');

        foreach ($items as $item) {
            if ($grouped->has($item->id)) {
                $item->childOrgUnits = $grouped[$item->id];
            }
        }

        return $items->where('orgunit_id', null);
    }
}