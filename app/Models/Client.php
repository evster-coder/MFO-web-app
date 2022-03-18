<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property string $birth_date
 * @property int|null $org_unit_id
 *
 * @property-read string $fullName
 * @property-read string $text
 * @property-read Collection|Loan[] $loans
 * @property-read Collection|ClientForm[] $clientForms
 * @property-read int|null $client_forms_count
 * @property-read int|null $loans_count
 * @property-read OrgUnit|null $orgUnit
 *
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static Builder|Client query()
 * @method static Builder|Client whereBirthDate($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereName($value)
 * @method static Builder|Client whereOrgUnitId($value)
 * @method static Builder|Client wherePatronymic($value)
 * @method static Builder|Client whereSurname($value)
 *
 * @mixin \Eloquent
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
    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'id');
    }

    /**
     * Заявки на выдачу займа
     *
     * @return HasMany
     */
    public function clientForms(): HasMany
    {
        return $this->hasMany(ClientForm::class, 'client_id', 'id');
    }

    /**
     * Займы
     *
     * @return HasManyThrough
     */
    public function loans(): HasManyThrough
    {
        return $this->hasManyThrough(
            Loan::class,
            ClientForm::class,
            'client_id',
            'client_form_id',
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
        /** @var ClientForm $lastClientForm */
        $lastClientForm = $this->clientForms->last();

        $result = $this->surname . " " . $this->name . " " . $this->patronymic . " ("
            . date(config('app.date_format', 'd-m-Y'), strtotime($this->birth_date))
            . ") Паспорт: ";

        if ($lastClientForm) {
            $result = $result . $lastClientForm->passport->passport_series . " "
                . $lastClientForm->passport->passport_number . " от "
                . date(
                    config('app.date_format', 'd-m-Y'),
                    strtotime($lastClientForm->passport->passport_date_issue)
                );
        } else {
            $result = $result . "Не указан";
        }

        return $result;
    }
}
