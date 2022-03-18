<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasRolesAndPermissions;

/**
 * Пользователь системы
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $full_name
 * @property int|null $org_unit_id
 * @property string|null $position
 * @property string|null $reason
 * @property bool|null $blocked
 * @property bool|null $need_change_password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read OrgUnit|null $orgUnit
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 *
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereBlocked($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereFullName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereNeedChangePassword($value)
 * @method static Builder|User whereOrgUnitId($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePosition($value)
 * @method static Builder|User whereReason($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 *
 * @mixin HasRolesAndPermissions
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, HasRolesAndPermissions;

    protected $guarded = [
        'id',
        'password',
        'remember_token',
    ];

    /**
     * @return BelongsTo
     */
    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'id');
    }

    /**
     * Можно ли установить в текущее подразделение
     *
     * @param int $id
     * @return bool
     */
    public function canSetOrgUnit(int $id): bool
    {
        if ($this->org_unit_id == $id) {
            return true;
        }

        /** @var OrgUnit[] $subtree */
        $subtree = OrgUnit::whereDescendantOrSelf($this->orgUnit)->get();
        foreach ($subtree as $node) {
            if ($node->id == $id) {
                return true;
            }
        }

        return false;
    }

    public function getDownUsers()
    {
        $orgUnit = OrgUnit::find(session('orgUnit'));

        return User::whereIn('users.org_unit_id',
            OrgUnit::whereDescendantOrSelf($orgUnit)
                ->pluck('id'));
    }
}
