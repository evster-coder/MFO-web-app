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

    protected $appends = ['status'];
    
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

    //атрибут для статуса заявки
    public function getStatusAttribute()
    {
        $securityAppr = $this->SecurityApproval;
        $directorAppr = $this->DirectorApproval;
        $loan = $this->Loan;

        if($loan)
            return 'open';
        elseif($securityAppr 
        && $directorAppr)
            return 'odobren';
        elseif($securityAppr)
            return 'odobren security';
        elseif($directorAppr)
            return 'odobren director';
        else return 'ne odobren';
    }

    public function Loan()
    {
        return $this->hasOne(Loan::class, 'clientform_id', 'id');
    }

    public function SecurityApproval()
    {
        return $this->belongsTo(SecurityApproval::class, 'security_approval_id', 'id');
    }

    public function DirectorApproval()
    {
        return $this->belongsTo(DirectorApproval::class, 'director_approval_id', 'id');
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function MaritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'maritalstatus_id', 'id');
    }

}
