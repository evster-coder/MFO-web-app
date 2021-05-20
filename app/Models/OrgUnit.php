<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Kalnoy\Nestedset\NodeTrait;

class OrgUnit extends Model
{
    use HasFactory;    
    use NodeTrait;


    protected $table = 'orgunits';

    //отключение полей updated_at, created_at
    public $timestamps = false;

    //заполняемые поля
    protected $fillable = [
    	'orgUnitCode',
    	'hasDictionaries',
    	'parent_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'orgunit_id');
    }

    public function parentOrgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id');
    }

    public function getDictsOrgUnit()
    {
        $orgunits = OrgUnit::whereAncestorOrSelf($this->id)->get();

        $i = $orgunits->count() - 1;
        while ($i >= 0)
            if($orgunits[$i]->hasDictionaries == true)
                break;
            else
                $i -= 1;

        if($i >= 0)
            return $orgunits[$i];
        else
            return $orgunits[0];
    }
}
