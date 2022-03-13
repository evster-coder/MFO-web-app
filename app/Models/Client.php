<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * Клиент
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string|null $patronymic
 * @property string $birthDate
 * @property int|null $orgunit_id
 *
 * @property-read string $fullName
 * @property-read string $text
 *
 * @property-read Collection|Loan[] $Loans
 * @property-read Collection|ClientForm[] $ClientForms
 */
class Client extends Model
{
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
     * @var string[]
     */
    protected $appends = ['text', 'fullName'];

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->surname . " " . $this->name . " " . $this->patronymic;
    }

    /**
     * Структурное подразделение
     *
     * @return BelongsTo
     */
    public function OrgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    /**
     * Заявки на выдачу займа
     *
     * @return HasMany
     */
    public function ClientForms(): HasMany
    {
        return $this->hasMany(ClientForm::class, 'client_id', 'id');
    }

    /**
     * Займы
     *
     * @return HasManyThrough
     */
    public function Loans(): HasManyThrough
    {
        return $this->hasManyThrough(
            Loan::class,
            ClientForm::class,
            'client_id',
            'clientform_id',
            'id',
            'id'
        );
    }

    /**
     * Подробная информация о клиенте
     *
     * @return string
     */
    public function getTextAttribute(): string
    {
        $lastClientForm = $this->ClientForms->last();

        $result = $this->surname . " " . $this->name . " " . $this->patronymic . " ("
            . date(config('app.date_format', 'd-m-Y'), strtotime($this->birthDate))
            . ") Паспорт: ";

        if ($lastClientForm) {
            $result = $result . $lastClientForm->Passport->passportSeries . " "
                . $lastClientForm->Passport->passportNumber . " от "
                . date(
                    config('app.date_format', 'd-m-Y'),
                    strtotime($lastClientForm->Passport->passportDateIssue)
                );
        } else {
            $result = $result . "Не указан";
        }

        return $result;
    }
}
