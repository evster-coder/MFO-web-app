<?php

namespace App\Models\DictsData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Рабочий стаж
 *
 * @property int $id
 * @property string $name
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
