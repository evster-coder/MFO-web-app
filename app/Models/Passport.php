<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Паспорт
 *
 * @property int $id
 * @property string $passport_series
 * @property string $passport_number
 * @property string $passport_date_issue
 * @property string $passport_issued_by
 * @property string|null $passport_department_code
 * @property string|null $passport_birthplace
 *
 * @method static Builder|Passport newModelQuery()
 * @method static Builder|Passport newQuery()
 * @method static Builder|Passport query()
 * @method static Builder|Passport whereId($value)
 * @method static Builder|Passport wherePassportBirthplace($value)
 * @method static Builder|Passport wherePassportDateIssue($value)
 * @method static Builder|Passport wherePassportDepartmentCode($value)
 * @method static Builder|Passport wherePassportIssuedBy($value)
 * @method static Builder|Passport wherePassportNumber($value)
 * @method static Builder|Passport wherePassportSeries($value)
 *
 * @mixin \Eloquent
 */
class Passport extends Model
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
