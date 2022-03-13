<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Роль
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|User[] $users
 */
class Role extends Model
{
    use HasFactory;

    /**
     * Администратор
     */
    const ADMIN_ROLE = 'admin';

    /**
     * Директор
     */
    const DIRECTOR_ROLE = 'director';

    /**
     * Кассир
     */
    const CASHIER_ROLE = 'cashier';

    /**
     * Служба безопасности
     */
    const SECURITY_ROLE = 'security';

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
    	return $this->belongsToMany(Permission::class, 'role_permission')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
    	return $this->belongsToMany(User::class, 'user_role')->withTimestamps();
    }

    /**
     * @param string $permSlug
     * @return bool
     */
    public function contains(string $permSlug): bool
    {
        return $this->permissions->contains('slug', $permSlug);
    }
}
