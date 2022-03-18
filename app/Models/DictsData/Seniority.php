<?php

namespace App\Models\DictsData;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Рабочий стаж
 *
 * @property int $id
 * @property string $name
 *
 * @method static Builder|Seniority newModelQuery()
 * @method static Builder|Seniority newQuery()
 * @method static Builder|Seniority query()
 * @method static Builder|Seniority whereId($value)
 * @method static Builder|Seniority whereName($value)
 *
 * @mixin \Eloquent
 */
class Seniority extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];
}
