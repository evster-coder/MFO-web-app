<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Право
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection|Role[] $roles
 * @property-read Collection|User[] $users
 */
class Permission extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
    	return $this->belongsToMany(Role::class, 'role_permission')->withTimestamps();
    }


    public function users()
    {
    	return $this->roles->users();
    }
}
