<?php

namespace App\Models\DictsData;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Срок займа
 *
 * @property int $id
 * @property int $days_amount
 *
 * @method static Builder|LoanTerm newModelQuery()
 * @method static Builder|LoanTerm newQuery()
 * @method static Builder|LoanTerm query()
 * @method static Builder|LoanTerm whereDaysAmount($value)
 * @method static Builder|LoanTerm whereId($value)
 *
 * @mixin \Eloquent
 */
class LoanTerm extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @var string[]
     */
    protected $fillable = [
        'days_amount',
    ];
}
