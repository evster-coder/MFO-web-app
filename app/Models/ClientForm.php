<?php

namespace App\Models;

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
 * @property int $orgunit_id
 * @property int|null $user_id
 * @property int|null $security_approval_id
 * @property int|null $director_approval_id
 * @property float $interestRate Процентная ставка
 * @property float $loanCost Сумма займа
 * @property string $loanDate Дата займа
 * @property int $loanTerm Срок займа
 * @property float|null $monthlyPayment
 * @property bool $isBankrupt
 * @property bool $hasCredits
 * @property string|null $cashierComment
 * @property string|null $mobilePhone
 * @property string|null $homePhone
 * @property string|null $snils
 * @property string|null $pensionerId
 * @property string $actualResidenceAddress
 * @property string $passportResidenceAddress
 * @property int|null $numberOfDependents
 * @property int|null $maritalstatus_id
 * @property int|null $seniority_id
 * @property int $passport_id
 * @property string $workPlaceName
 * @property string|null $workPlaceAddress
 * @property string|null $workPlacePosition
 * @property string|null $workPlacePhone
 * @property float $constainIncome
 * @property float $additionalIncome
 *
 * @property-read string $status Статус заявки
 *
 * @property-read Loan $Loan
 * @property-read SecurityApproval $SecurityApproval
 * @property-read DirectorApproval $DirectorApproval
 * @property-read Client $Client
 * @property-read MaritalStatus $MaritalStatus
 * @property-read User $User
 * @property-read Passport $Passport
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
        'hasCredits',
    ];

    /**
     * Статус заявки
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        $securityAppr = $this->SecurityApproval;
        $directorAppr = $this->DirectorApproval;
        $loan = $this->Loan;

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
    public function Loan(): HasOne
    {
        return $this->hasOne(Loan::class, 'clientform_id', 'id');
    }

    /**
     * Одобрение службы безопасности
     *
     * @return BelongsTo
     */
    public function SecurityApproval(): BelongsTo
    {
        return $this->belongsTo(SecurityApproval::class, 'security_approval_id', 'id');
    }

    /**
     * Одобрение директора
     *
     * @return BelongsTo
     */
    public function DirectorApproval(): BelongsTo
    {
        return $this->belongsTo(DirectorApproval::class, 'director_approval_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function Client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function MaritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class, 'maritalstatus_id', 'id');
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
    public function Passport(): BelongsTo
    {
        return $this->belongsTo(Passport::class, 'passport_id', 'id');
    }
}
