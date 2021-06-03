<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
    	'orgunit_id',
    	'clientform_id',
    	'loanNumber',
    	'loanConclusionDate',
    	'loanClosingDate',
    	'statusOpen'
    ];

    public function OrgUnit()
    {
    	return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }

    public function ClientForm()
    {
    	return $this->belongsTo(ClientForm::class, 'clientform_id', 'id');
    }

    public function Payments()
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }
}
