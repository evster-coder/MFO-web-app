<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Займ
 *
 * @property int $id
 * @property int $org_unit_id
 * @property int $client_form_id
 * @property string $loan_number
 * @property string $loan_conclusion_date
 * @property string $loan_closing_date
 * @property bool $status_open
 *
 * @property-read OrgUnit $orgUnit
 * @property-read ClientForm $clientForm
 * @property-read Collection|Payment[] $payments
 *
 * @method static Builder|Loan newModelQuery()
 * @method static Builder|Loan newQuery()
 * @method static Builder|Loan query()
 * @method static Builder|Loan whereClientFormId($value)
 * @method static Builder|Loan whereId($value)
 * @method static Builder|Loan whereLoanClosingDate($value)
 * @method static Builder|Loan whereLoanConclusionDate($value)
 * @method static Builder|Loan whereLoanNumber($value)
 * @method static Builder|Loan whereOrgUnitId($value)
 * @method static Builder|Loan whereStatusOpen($value)
 *
 * @mixin \Eloquent
 */
class Loan extends Model
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
    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function clientForm(): BelongsTo
    {
        return $this->belongsTo(ClientForm::class, 'client_form_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }
}
