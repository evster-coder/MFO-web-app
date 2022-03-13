<?php

namespace App\Models;

use Carbon\Carbon;
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
 * @property string $FIO
 * @property int|null $orgunit_id
 * @property string|null $position
 * @property string|null $reason
 * @property bool|null $blocked
 * @property bool|null $needChangePassword
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read OrgUnit $OrgUnit
 *
 * @mixin HasRolesAndPermissions
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
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    /**
     * Можно ли установить в текущее подразделение
     *
     * @param int $id
     * @return bool
     */
    public function canSetOrgUnit(int $id): bool
    {
        if ($this->orgunit_id == $id) {
            return true;
        }

        /** @var OrgUnit[] $subtree */
        $subtree = OrgUnit::whereDescendantOrSelf($this->OrgUnit)->get();
        foreach ($subtree as $node) {
            if ($node->id == $id) {
                return true;
            }
        }

        return false;
    }

    public function getDownUsers()
    {
        /** @var OrgUnit $orgUnit */
        $orgUnit = OrgUnit::find(session('OrgUnit'));

        return User::whereIn('users.orgunit_id',
            OrgUnit::whereDescendantOrSelf($orgUnit)
                ->pluck('id'));
    }
}
