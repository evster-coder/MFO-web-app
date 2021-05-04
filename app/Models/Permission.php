<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Role;
use App\Models\User;

class Permission extends Model
{
    use HasFactory;


    //заполняемые поля
    protected $fillable = [
        'name',
        'slug',

    ];



    //все роли, содержащие это право
    public function roles()
    {
    	return $this->belongsToMany(Role::class, 'role_permission')->withTimestamps();
    }

    //все пользователи, содержащие это право
    public function users()
    {
    	return $this->roles->users();
    }
}
