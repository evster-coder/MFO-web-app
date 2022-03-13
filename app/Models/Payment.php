<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Платеж
 *
 * @property int $id
 * @property Carbon $paymentDate
 * @property float $paymentSum
 * @property int $loan_id
 * @property int $user_id
 * @property int $orgunit_id
 *
 * @property-read Loan $Loan
 * @property-read User $User
 * @property-read OrgUnit $OrgUnit
 */
class Payment extends Model
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
     * Займ
     *
     * @return BelongsTo
     */
    public function Loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }

    /**
     * Оформитель
     *
     * @return BelongsTo
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function OrgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }
}
