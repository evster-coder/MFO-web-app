<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property string $data_type
 *
 * @property-read Collection|ParamValue[] $paramValues
 * @property-read int|null $param_values_count
 *
 * @method static Builder|OrgUnitParam newModelQuery()
 * @method static Builder|OrgUnitParam newQuery()
 * @method static Builder|OrgUnitParam query()
 * @method static Builder|OrgUnitParam whereDataType($value)
 * @method static Builder|OrgUnitParam whereId($value)
 * @method static Builder|OrgUnitParam whereName($value)
 * @method static Builder|OrgUnitParam whereSlug($value)
 *
 * @mixin \Eloquent
 */
class OrgUnitParam extends Model
{
    use HasFactory;

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
     * @param int $org_unit_id
     * @return $this
     */
    public function getClosestValue(int $org_unit_id)
    {
        /** @var OrgUnit $orgUnit */
        $orgUnit = OrgUnit::find($org_unit_id);
        $values = ParamValue::where('org_unit_param_id', $this->id)->get();
        $paramValue = null;

        while ($orgUnit != null) {
            $paramValue = $values->firstWhere('org_unit_id', $orgUnit->id);

            if ($paramValue != null) {
                break;
            }

            $orgUnit = $orgUnit->parent()->first();
        }

        if ($paramValue == null) {
            return $this;
        } else {
            return $paramValue->load('orgUnitParam');
        }
    }

    /**
     * Значения параметров
     *
     * @return HasMany
     */
    public function paramValues(): HasMany
    {
        return $this->hasMany(ParamValue::class,
            'org_unit_params',
            'org_unit_param_id'
        );
    }
}
