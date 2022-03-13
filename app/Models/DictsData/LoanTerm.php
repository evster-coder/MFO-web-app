<?php

namespace App\Models\DictsData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Срок займа
 *
 * @property int $id
 * @property int $daysAmount
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
        'daysAmount',
    ];
}
