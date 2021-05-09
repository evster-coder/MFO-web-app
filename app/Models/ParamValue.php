<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\OrgUnitParam;
use App\Models\OrgUnit;

class ParamValue extends Model
{
    use HasFactory;

    //отключение полей updated_at, created_at
    public $timestamps = false;

    protected $fillable = [
    	'orgunit_id',
    	'orgunit_param_id',
    	'dataAsString',
    	'dataAsDate',
    	'dataAsNumber',
    ];

    public function OrgUnitParam()
    {
        return $this->belongsTo(OrgUnitParam::class, 'orgunit_param_id', 'id');
    }

    public function OrgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }
}
