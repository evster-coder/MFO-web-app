<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Значение параметра
 *
 * @property int $id
 * @property int $org_unit_id
 * @property int $org_unit_param_id
 * @property string $data_as_string
 * @property string $data_as_date
 * @property float $data_as_number
 *
 * @property-read OrgUnitParam $orgUnitParam
 * @property-read OrgUnit $orgUnit
 *
 * @method static Builder|ParamValue newModelQuery()
 * @method static Builder|ParamValue newQuery()
 * @method static Builder|ParamValue query()
 * @method static Builder|ParamValue whereDataAsDate($value)
 * @method static Builder|ParamValue whereDataAsNumber($value)
 * @method static Builder|ParamValue whereDataAsString($value)
 * @method static Builder|ParamValue whereId($value)
 * @method static Builder|ParamValue whereOrgUnitId($value)
 * @method static Builder|ParamValue whereOrgUnitParamId($value)
 *
 * @mixin \Eloquent
 */
class ParamValue extends Model
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
     * @return BelongsTo
     */
    public function orgUnitParam(): BelongsTo
    {
        return $this->belongsTo(OrgUnitParam::class, 'org_unit_param_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'id');
    }
}
