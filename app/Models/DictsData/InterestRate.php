<?php

namespace App\Models\DictsData;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Процентная ставка
 *
 * @property int $id
 * @property float $percent_value
 *
 * @method static Builder|InterestRate newModelQuery()
 * @method static Builder|InterestRate newQuery()
 * @method static Builder|InterestRate query()
 * @method static Builder|InterestRate whereId($value)
 * @method static Builder|InterestRate wherePercentValue($value)
 *
 * @mixin \Eloquent
 */
class InterestRate extends Model
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
}
