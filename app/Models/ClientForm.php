<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DictsData\MaritalStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Заявка на выдачу займа
 *
 * @property int $id
 * @property int $client_id
 * @property int $org_unit_id
 * @property int|null $user_id
 * @property int|null $security_approval_id
 * @property int|null $director_approval_id
 * @property float $interest_rate Процентная ставка
 * @property float $loan_cost Сумма займа
 * @property string $loan_date Дата займа
 * @property int $loan_term Срок займа
 * @property float|null $monthly_payment
 * @property bool $is_bankrupt
 * @property bool $has_credits
 * @property string|null $cashier_comment
 * @property string|null $mobile_phone
 * @property string|null $home_phone
 * @property string|null $snils
 * @property string|null $pensioner_id
 * @property string $actual_residence_address
 * @property string $passport_residence_address
 * @property int|null $number_of_dependents
 * @property int|null $marital_status_id
 * @property int|null $seniority_id
 * @property int $passport_id
 * @property string $work_place_name
 * @property string|null $work_place_address
 * @property string|null $work_place_position
 * @property string|null $work_place_phone
 * @property float $constain_income
 * @property float $additional_income
 *
 * @property-read string $status Статус заявки
 * @property-read Loan $loan
 * @property-read SecurityApproval $securityApproval
 * @property-read DirectorApproval $directorApproval
 * @property-read Client $client
 * @property-read MaritalStatus $maritalStatus
 * @property-read User $user
 * @property-read Passport $passport
 *
 * @method static Builder|ClientForm newModelQuery()
 * @method static Builder|ClientForm newQuery()
 * @method static Builder|ClientForm query()
 * @method static Builder|ClientForm whereActualResidenceAddress($value)
 * @method static Builder|ClientForm whereAdditionalIncome($value)
 * @method static Builder|ClientForm whereCashierComment($value)
 * @method static Builder|ClientForm whereClientId($value)
 * @method static Builder|ClientForm whereConstainIncome($value)
 * @method static Builder|ClientForm whereDirectorApprovalId($value)
 * @method static Builder|ClientForm whereHasCredits($value)
 * @method static Builder|ClientForm whereHomePhone($value)
 * @method static Builder|ClientForm whereId($value)
 * @method static Builder|ClientForm whereInterestRate($value)
 * @method static Builder|ClientForm whereIsBankrupt($value)
 * @method static Builder|ClientForm whereLoanCost($value)
 * @method static Builder|ClientForm whereLoanDate($value)
 * @method static Builder|ClientForm whereLoanTerm($value)
 * @method static Builder|ClientForm whereMaritalstatusId($value)
 * @method static Builder|ClientForm whereMobilePhone($value)
 * @method static Builder|ClientForm whereMonthlyPayment($value)
 * @method static Builder|ClientForm whereNumberOfDependents($value)
 * @method static Builder|ClientForm whereOrgUnitId($value)
 * @method static Builder|ClientForm wherePassportId($value)
 * @method static Builder|ClientForm wherePassportResidenceAddress($value)
 * @method static Builder|ClientForm wherePensionerId($value)
 * @method static Builder|ClientForm whereSecurityApprovalId($value)
 * @method static Builder|ClientForm whereSeniorityId($value)
 * @method static Builder|ClientForm whereSnils($value)
 * @method static Builder|ClientForm whereUserId($value)
 * @method static Builder|ClientForm whereWorkPlaceAddress($value)
 * @method static Builder|ClientForm whereWorkPlaceName($value)
 * @method static Builder|ClientForm whereWorkPlacePhone($value)
 * @method static Builder|ClientForm whereWorkPlacePosition($value)
 *
 * @mixin \Eloquent
 */
class ClientForm extends Model
{
    use HasFactory;

    const CHECK_CREDITS_LINK = 'https://r22.fssp.gov.ru/';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $appends = ['status'];

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
        'has_credits',
    ];

    /**
     * Статус заявки
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        $securityAppr = $this->securityApproval;
        $directorAppr = $this->directorApproval;
        $loan = $this->loan;

        if ($loan) {
            return 'Заключен договор';
        }

        if ($securityAppr) {
            if (!$securityAppr->approval) {
                return 'Отклонена';
            }
        }
        if ($directorAppr) {
            if (!$directorAppr->approval) {
                return 'Отклонена';
            }
        }

        if (!$directorAppr || !$securityAppr) {
            return 'В рассмотрении';
        } else {
            return 'Одобрена';
        }
    }

    /**
     * Займ
     *
     * @return HasOne
     */
    public function loan(): HasOne
    {
        return $this->hasOne(Loan::class, 'client_form_id', 'id');
    }

    /**
     * Одобрение службы безопасности
     *
     * @return BelongsTo
     */
    public function securityApproval(): BelongsTo
    {
        return $this->belongsTo(SecurityApproval::class, 'security_approval_id', 'id');
    }

    /**
     * Одобрение директора
     *
     * @return BelongsTo
     */
    public function directorApproval(): BelongsTo
    {
        return $this->belongsTo(DirectorApproval::class, 'director_approval_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function MaritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id', 'id');
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
    public function passport(): BelongsTo
    {
        return $this->belongsTo(Passport::class, 'passport_id', 'id');
    }
}
