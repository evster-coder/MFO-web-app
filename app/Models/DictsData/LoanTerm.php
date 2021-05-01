<?php

namespace App\Models\DictsData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTerm extends Model
{
    use HasFactory;

    protected $fillable = [
    	'daysAmount',
    ];
}
