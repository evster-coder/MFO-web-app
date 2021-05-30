<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorApproval extends Model
{
    use HasFactory;

    //отключение полей updated_at, created_at
    public $timestamps = false;
    protected $fillable = [
    	'user_id',
    	'approval',
    	'comment',
    	'approvalDate'
    ];

    public function User()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
