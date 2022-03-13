<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Паспорт
 *
 * @property int $id
 * @property string $passportSeries
 * @property string $passportNumber
 * @property string $passportDateIssue
 * @property string $passportIssuedBy
 * @property string|null $passportDepartamentCode
 * @property string|null $passportBirthplace
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
