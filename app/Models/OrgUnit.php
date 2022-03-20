<?php

namespace App\Models;

use Database\Factories\OrgUnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Kalnoy\Nestedset\QueryBuilder;

/**
 * Организационное подразделение
 *
 * @property int $id
 * @property string $org_unit_code
 * @property bool $has_dictionaries
 * @property int $_lft
 * @property int $_rgt
 * @property int $parent_id
 *
 * @property-read Collection|User[] $users
 * @property-read Collection $params
 * @property-read OrgUnit $parentOrgUnit
 * @property-read \Kalnoy\Nestedset\Collection|OrgUnit[] $children
 * @property-read int|null $children_count
 * @property-read OrgUnit|null $parent
 * @property-read int|null $users_count
 *
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static QueryBuilder|OrgUnit ancestorsAndSelf($id, array $columns = [])
 * @method static QueryBuilder|OrgUnit ancestorsOf($id, array $columns = [])
 * @method static QueryBuilder|OrgUnit applyNestedSetScope(?string $table = null)
 * @method static QueryBuilder|OrgUnit countErrors()
 * @method static QueryBuilder|OrgUnit d()
 * @method static QueryBuilder|OrgUnit defaultOrder(string $dir = 'asc')
 * @method static QueryBuilder|OrgUnit descendantsAndSelf($id, array $columns = [])
 * @method static QueryBuilder|OrgUnit descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static OrgUnitFactory factory(...$parameters)
 * @method static QueryBuilder|OrgUnit fixSubtree($root)
 * @method static QueryBuilder|OrgUnit fixTree($root = null)
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static QueryBuilder|OrgUnit getNodeData($id, $required = false)
 * @method static QueryBuilder|OrgUnit getPlainNodeData($id, $required = false)
 * @method static QueryBuilder|OrgUnit getTotalErrors()
 * @method static QueryBuilder|OrgUnit hasChildren()
 * @method static QueryBuilder|OrgUnit hasParent()
 * @method static QueryBuilder|OrgUnit isBroken()
 * @method static QueryBuilder|OrgUnit leaves(array $columns = [])
 * @method static QueryBuilder|OrgUnit makeGap(int $cut, int $height)
 * @method static QueryBuilder|OrgUnit moveNode($key, $position)
 * @method static QueryBuilder|OrgUnit newModelQuery()
 * @method static QueryBuilder|OrgUnit newQuery()
 * @method static QueryBuilder|OrgUnit orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static QueryBuilder|OrgUnit orWhereDescendantOf($id)
 * @method static QueryBuilder|OrgUnit orWhereNodeBetween($values)
 * @method static QueryBuilder|OrgUnit orWhereNotDescendantOf($id)
 * @method static QueryBuilder|OrgUnit query()
 * @method static QueryBuilder|OrgUnit rebuildSubtree($root, array $data, $delete = false)
 * @method static QueryBuilder|OrgUnit rebuildTree(array $data, $delete = false, $root = null)
 * @method static QueryBuilder|OrgUnit reversed()
 * @method static QueryBuilder|OrgUnit root(array $columns = [])
 * @method static QueryBuilder|OrgUnit whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static QueryBuilder|OrgUnit whereAncestorOrSelf($id)
 * @method static QueryBuilder|OrgUnit whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static QueryBuilder|OrgUnit whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static QueryBuilder|OrgUnit whereHasDictionaries($value)
 * @method static QueryBuilder|OrgUnit whereId($value)
 * @method static QueryBuilder|OrgUnit whereIsAfter($id, $boolean = 'and')
 * @method static QueryBuilder|OrgUnit whereIsBefore($id, $boolean = 'and')
 * @method static QueryBuilder|OrgUnit whereIsLeaf()
 * @method static QueryBuilder|OrgUnit whereIsRoot()
 * @method static QueryBuilder|OrgUnit whereLft($value)
 * @method static QueryBuilder|OrgUnit whereNodeBetween($values, $boolean = 'and', $not = false)
 * @method static QueryBuilder|OrgUnit whereNotDescendantOf($id)
 * @method static QueryBuilder|OrgUnit whereOrgUnitCode($value)
 * @method static QueryBuilder|OrgUnit whereParentId($value)
 * @method static QueryBuilder|OrgUnit whereRgt($value)
 * @method static QueryBuilder|OrgUnit withDepth(string $as = 'depth')
 * @method static QueryBuilder|OrgUnit withoutRoot()
 *
 * @mixin \Eloquent
 * @mixin NodeTrait
 */
class OrgUnit extends Model
{
    use HasFactory, NodeTrait;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'org_unit_code',
        '$this->has_dictionaries',
        'parent_id',
    ];

    /**
     * Пользователи подразделения
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'org_unit_id');
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
        $orgUnitParams = OrgUnitParam::query()->orderBy('name')->get();

        $paramsArr = [];
        foreach ($orgUnitParams as $param) {
            $paramsArr[] = $param->getClosestValue($this->id);
        }

        return collect($paramsArr);
    }

    /**
     * Справочники подразделения
     *
     * @return OrgUnit
     */
    public function getDictsOrgUnit(): OrgUnit
    {
        $orgUnits = self::whereAncestorOrSelf($this->id)->get();

        $i = $orgUnits->count() - 1;
        while ($i >= 0) {
            if ($orgUnits[$i]->has_dictionaries == true) {
                break;
            } else {
                $i -= 1;
            }
        }

        if ($i >= 0) {
            return $orgUnits[$i];
        } else {
            return $orgUnits[0];
        }
    }
}
