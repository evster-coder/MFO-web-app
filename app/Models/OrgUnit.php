<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Организационное подразделение
 *
 * @property int $id
 * @property string $orgUnitCode
 * @property bool $hasDictionaries
 * @property int $_lft
 * @property int $_rgt
 * @property int $parent_id
 *
 * @property-read Collection|User[] $users
 * @property-read Collection $params
 * @property-read OrgUnit $parentOrgUnit
 */
class OrgUnit extends Model
{
    use HasFactory, NodeTrait;

    /**
     * @var string
     */
    protected $table = 'orgunits';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'orgUnitCode',
        'hasDictionaries',
        'parent_id',
    ];

    /**
     * Пользователи подразделения
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'orgunit_id');
    }

    /**
     * Родительское подразделение
     *
     * @return BelongsTo
     */
    public function parentOrgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id');
    }

    /**
     * Параметры подразделения
     *
     * @return Collection
     */
    public function params(): Collection
    {
        $orgunitParams = OrgUnitParam::orderBy('name')->get();

        $paramsArr = [];
        foreach ($orgunitParams as $param) {
            $paramsArr[] = $param->getClosestValue($this->id);
        }

        return collect($paramsArr);
    }

    /**
     * Справочники подразделения
     *
     * @return mixed
     */
    public function getDictsOrgUnit()
    {
        $orgunits = self::whereAncestorOrSelf($this->id)->get();

        $i = $orgunits->count() - 1;
        while ($i >= 0) {
            if ($orgunits[$i]->hasDictionaries == true) {
                break;
            } else {
                $i -= 1;
            }
        }

        if ($i >= 0) {
            return $orgunits[$i];
        } else {
            return $orgunits[0];
        }
    }
}
