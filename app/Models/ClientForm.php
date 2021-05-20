<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\DictsData\MaritalStatus;

class ClientForm extends Model
{
    use HasFactory;

    //отключение полей updated_at, created_at
    public $timestamps = false;
    
    protected $fillable = [
    	'client_id',
    	'orgunit_id',
    	'user_id',

    	'security_approval_id',
    	'director_approval_id',

    	'interestRate',
    	'loanCost',
    	'loanDate',
    	'loanMaturityDate',
    	'monthlyPayment',
        'loanTerm',

    	'isBankrupt',
    	'cashierComment',

    	'mobilePhone',
    	'homePhone',

    	'snils',
        'pensionerId',
    	'actualResidenceAddress',
    	'numberOfDependents',
    	'maritalstatus_id',
    	'seniority_id',

    	'passportSeries',
    	'passportNumber',
    	'passportDateIssue',
    	'passportIssuedBy',
    	'passportResidenceAddress',
    	'passportDepartamentCode',
    	'passportBirthplace',

    	'workPlaceName',
    	'workPlaceAddress',
    	'workPlacePosition',
    	'workPlacePhone',
    	'constainIncome',
    	'additionalIncome'
    ];

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function MaritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'maritalstatus_id', 'id');
    }

}
