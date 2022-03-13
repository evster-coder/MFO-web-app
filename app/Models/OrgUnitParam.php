<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Параметры подразделения
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $dataType
 *
 * @property-read Collection|ParamValue[] $ParamValues
 */
class OrgUnitParam extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'orgunit_params';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Значение параметра для подразделения
     *
     * @param int $orgunit_id
     * @return $this
     */
    public function getClosestValue(int $orgunit_id)
    {
        /** @var OrgUnit $orgUnit */
        $orgUnit = OrgUnit::find($orgunit_id);
        $values = ParamValue::where('orgunit_param_id', $this->id)->get();
        $paramValue = null;

        while ($orgUnit != null) {
            $paramValue = $values->firstWhere('orgunit_id', $orgUnit->id);

            if ($paramValue != null) {
                break;
            }

            $orgUnit = $orgUnit->parent()->first();
        }

        if ($paramValue == null) {
            return $this;
        } else {
            return $paramValue->load('OrgUnitParam');
        }
    }

    /**
     * Значения параметров
     *
     * @return HasMany
     */
    public function ParamValues(): HasMany
    {
        return $this->hasMany(ParamValue::class,
            'orgunit_params',
            'orgunit_param_id'
        );
    }
}
