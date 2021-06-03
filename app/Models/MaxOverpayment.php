<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxOverpayment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
    	'dateFrom',
    	'dateTo',
    	'multiplicity',
    ];

}
