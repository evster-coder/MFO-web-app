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

    protected $appends = ['text', 'fullName'];

    public function getFullNameAttribute()
    {
        return $this->surname . " " . $this->name . " " . $this->patronymic;
    }

    public function OrgUnit()
    {
    	return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    public function ClientForms()
    {
        return $this->hasMany(ClientForm::class, 'client_id', 'id');
    }

    //аттрибут текста для select2
    public function getTextAttribute()
    {
        $lastClientForm = $this->ClientForms->last();

        $result = $this->surname . " " . $this->name . " " . $this->patronymic . " ("
            . date("d-m-Y", strtotime($this->birthDate)) . ") Паспорт: ";
        if($lastClientForm)
            $result = $result . $lastClientForm->Passport->passportSeries . " " . $lastClientForm->Passport->passportNumber . " от " . date('d-m-Y', strtotime($lastClientForm->Passport->passportDateIssue));
        else
            $result = $result . "Не указан";

        return $result;
    }
}
