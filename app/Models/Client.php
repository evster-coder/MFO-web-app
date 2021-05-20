<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\OrgUnit;

class Client extends Model
{
    //отключение полей updated_at, created_at
    public $timestamps = false;
    
    protected $fillable = [
    	'surname',
    	'name',
    	'patronymic',
    	'birthDate',
    	'orgunit_id',
    ];

    public function OrgUnit()
    {
    	return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    public function ClientForms()
    {
        return $this->hasMany(ClientForm::class, 'client_id', 'id');
    }
}
