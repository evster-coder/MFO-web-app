<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Permission;
use App\Models\User;

class Role extends Model
{
    use HasFactory;
    //все права этой роли
    public function permissions()
    {
    	return $this->belongsToMany(Permission::class, 'role_permission')->withTimestamps();
    }

    //все пользователи с этой ролью
    public function users()
    {
    	return $thos->belongsToMany(User::class, 'user_role')->withTimestamps();
    }
}
