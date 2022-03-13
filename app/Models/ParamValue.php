<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Значение параметра
 *
 * @property int $id
 * @property int $orgunit_id
 * @property int $orgunit_param_id
 * @property string $dataAsString
 * @property string $dataAsDate
 * @property float $dataAsNumber
 *
 * @property-read OrgUnitParam $OrgUnitParam
 * @property-read OrgUnit $OrgUnit
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
    public function OrgUnitParam(): BelongsTo
    {
        return $this->belongsTo(OrgUnitParam::class, 'orgunit_param_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function OrgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }
}
