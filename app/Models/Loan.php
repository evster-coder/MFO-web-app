<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Займ
 *
 * @property int $id
 * @property int $orgunit_id
 * @property int $clientform_id
 * @property string $loanNumber
 * @property string $loanConclusionDate
 * @property string $loanClosingDate
 * @property bool $statusOpen
 *
 * @property-read OrgUnit $OrgUnit
 * @property-read ClientForm $ClientForm
 * @property-read Collection|Payment[] $Payments
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
    public function OrgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function ClientForm(): BelongsTo
    {
        return $this->belongsTo(ClientForm::class, 'clientform_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function Payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }
}
