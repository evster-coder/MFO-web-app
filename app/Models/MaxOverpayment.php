<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Максимальная переплата
 *
 * @property int $id
 * @property string|null $date_from
 * @property string|null $date_to
 * @property float $multiplicity
 *
 * @method static Builder|MaxOverpayment newModelQuery()
 * @method static Builder|MaxOverpayment newQuery()
 * @method static Builder|MaxOverpayment query()
 * @method static Builder|MaxOverpayment whereDateFrom($value)
 * @method static Builder|MaxOverpayment whereDateTo($value)
 * @method static Builder|MaxOverpayment whereId($value)
 * @method static Builder|MaxOverpayment whereMultiplicity($value)
 *
 * @mixin \Eloquent
 */
class MaxOverpayment extends Model
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
