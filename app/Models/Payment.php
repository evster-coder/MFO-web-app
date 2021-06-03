<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $timestamps = false;
	    
	//отключение полей updated_at, created_at
    protected $fillable = [
    	'paymentSum',
    	'approval',
    	'loan_id',
    	'paymentDate',
        'user_id',
        'orgunit_id'
    ];

    public function Loan()
    {
    	return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function OrgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_id', 'id');
    }
}
