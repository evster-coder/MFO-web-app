<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Traits\HasRolesAndPermissions;

use App\Models\OrgUnit;

class User extends Authenticatable
{
    use HasFactory, HasRolesAndPermissions;

    //заполняемые поля
    protected $fillable = [
        'username',
        'password',

        'FIO',
        'orgunit_id',
        'position',
        'reason',
        'blocked',
        'needChangePassword'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    public function canSetOrgUnit($id)
    {
        if($this->orgunit_id == $id)
            return true;

        $subtree = OrgUnit::whereDescendantOrSelf($this->OrgUnit)->get();
        foreach ($subtree as $node)
            if($node->id == $id)
                return true;
   
        return false;
    }

    public function getDownUsers()
    {
        $orgUnit = OrgUnit::find(session('OrgUnit'));
        return User::whereIn('users.orgunit_id',
                         OrgUnit::whereDescendantOrSelf($orgUnit)
                            ->pluck('id'));
    }


}
