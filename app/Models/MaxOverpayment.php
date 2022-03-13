<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Максимальная переплата
 *
 * @property int $id
 * @property string|null $dateFrom
 * @property string|null $dateTo
 * @property float $multiplicity
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
