<?php

namespace App\Models\DictsData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Процентная ставка
 *
 * @property int $id
 * @property float $percentValue
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
        'percentValue',
    ];
}
