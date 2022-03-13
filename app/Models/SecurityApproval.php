<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Одобрение службы безопасности
 *
 * @property int $id
 * @property int|null $user_id
 * @property bool $approval
 * @property string|null $comment
 * @property Carbon $approvalDate
 *
 * @property-read User $User
 */
class SecurityApproval extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @return BelongsTo
     */
    public function User(): BelongsTo
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
