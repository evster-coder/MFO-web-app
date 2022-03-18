<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Одобрение директора
 *
 * @property int $id
 * @property int|null $user_id
 * @property bool $approval
 * @property string|null $comment
 * @property Carbon $approval_date
 *
 * @property-read User $User
 *
 * @method static Builder|DirectorApproval newModelQuery()
 * @method static Builder|DirectorApproval newQuery()
 * @method static Builder|DirectorApproval query()
 * @method static Builder|DirectorApproval whereApproval($value)
 * @method static Builder|DirectorApproval whereApprovalDate($value)
 * @method static Builder|DirectorApproval whereComment($value)
 * @method static Builder|DirectorApproval whereId($value)
 * @method static Builder|DirectorApproval whereUserId($value)
 *
 * @mixin \Eloquent
 */
class DirectorApproval extends Model
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
