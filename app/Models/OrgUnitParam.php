<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\OrgUnit;
use App\Models\ParamValue;

class OrgUnitParam extends Model
{
    use HasFactory;

    protected $table = 'orgunit_params';
    //отключение полей updated_at, created_at
    public $timestamps = false;

    //заполняемые поля
	protected $fillable = [
		'name',
		'slug',
		'dataType',
	];


	//получаем значение параметра для подразделения
	public function getClosestValue($orgunit_id)
	{
		$orgunit = OrgUnit::find($orgunit_id);
		$values = ParamValue::where('orgunit_param_id', $this->id)->get();
		$paramValue = null;
		while($orgunit != null)
		{ 
			$paramValue = $values->firstWhere('orgunit_id', $orgunit->id);

			if($paramValue != null)
				break;

			$orgunit = $orgunit->parent()->first();
		}
		if($paramValue == null)
			return $this;
		else
			return $paramValue->load('OrgUnitParam');
	}

    public function ParamValues()
    {
        return $this->hasMany(ParamValue::class, 'orgunit_params', 'orgunit_param_id', 'id');
    }


}
