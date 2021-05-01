<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class OrgUnit extends Model
{
    use HasFactory;    
    use HasRecursiveRelationships;


    protected $table = 'orgunits';

    //отключение полей updated_at, created_at
    public $timestamps = false;


    //устанавилваем id для родительского элемента
    public function getParentKeyName()
    {
        return 'orgunit_id';
    }

    //заполняемые поля
    protected $fillable = [
    	'orgUnitCode',
    	'hasDictionaries',
    	'orgunit_id',
    ];


    public function parentOrgUnit()
    {
    	return $this->belongsTo(OrgUnit::class, 'orgunit_id');
    }

    public function childsOrgUnit()
    {
    	return $this->hasMany(OrgUnit::class, 'orgunit_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'orgunit_id');
    }

}
