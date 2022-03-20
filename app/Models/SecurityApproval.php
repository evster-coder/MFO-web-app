<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
 * @property Carbon $approval_date
 *
 * @property-read User $user
 *
 * @method static Builder|SecurityApproval newModelQuery()
 * @method static Builder|SecurityApproval newQuery()
 * @method static Builder|SecurityApproval query()
 * @method static Builder|SecurityApproval whereApproval($value)
 * @method static Builder|SecurityApproval whereApprovalDate($value)
 * @method static Builder|SecurityApproval whereComment($value)
 * @method static Builder|SecurityApproval whereId($value)
 * @method static Builder|SecurityApproval whereUserId($value)
 *
 * @mixin \Eloquent
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
    public function user(): BelongsTo
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
