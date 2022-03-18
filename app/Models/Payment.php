<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Платеж
 *
 * @property int $id
 * @property Carbon $payment_date
 * @property float $payment_sum
 * @property int $loan_id
 * @property int $user_id
 * @property int $org_unit_id
 *
 * @property-read Loan $loan
 * @property-read User $user
 * @property-read OrgUnit $orgUnit
 *
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereLoanId($value)
 * @method static Builder|Payment whereOrgUnitId($value)
 * @method static Builder|Payment wherePaymentDate($value)
 * @method static Builder|Payment wherePaymentSum($value)
 * @method static Builder|Payment whereUserId($value)
 *
 * @mixin \Eloquent
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
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }

    /**
     * Оформитель
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'id');
    }
}
