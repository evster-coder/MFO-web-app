<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    use HasFactory;

    //отключение полей updated_at, created_at
    public $timestamps = false;

    //заполняемые поля
    protected $fillable = [
    	'passportSeries',
    	'passportNumber',
    	'passportDateIssue',
    	'passportIssuedBy',
    	'passportDepartamentCode',
    	'passportBirthplace',

    ];

}
