<?php

namespace App\Models\DictsData;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Семейное положение
 *
 * @property int $id
 * @property string name
 *
 * @method static Builder|MaritalStatus newModelQuery()
 * @method static Builder|MaritalStatus newQuery()
 * @method static Builder|MaritalStatus query()
 * @method static Builder|MaritalStatus whereId($value)
 * @method static Builder|MaritalStatus whereName($value)
 *
 * @mixin \Eloquent
 */
class MaritalStatus extends Model
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
