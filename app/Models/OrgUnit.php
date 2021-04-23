<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgUnit extends Model
{
    use HasFactory;

    protected $table = 'orgunits';

    //отключение полей updated_at, created_at
    public $timestamps = false;

    //заполняемые поля
    protected $fillable = [
    	'orgUnitCode',
    	'hasDictionaries',
    	'orgunit_id',
    ];

    public function parentOrgUnit()
    {
    	return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    public function childsOrgUnit()
    {
    	return $this->hasMany(OrgUnit::class, 'orgunit_id', 'id');
    }

}
