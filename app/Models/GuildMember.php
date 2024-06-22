<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property-read Guild $guild
 * @property-read User $user
 * @property-read int $role
 *
 * @method static Builder|GuildMember newModelQuery()
 * @method static Builder|GuildMember newQuery()
 * @method static Builder|GuildMember query()
 * @method static where(string $column, mixed $value)
 *
 * @mixin \Eloquent
 */
class GuildMember extends Pivot
{
    protected $table = 'guild_members';

    protected $fillable = [
        'user_id',
        'guild_id',
        'role',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guild(): BelongsTo
    {
        return $this->belongsTo(Guild::class);
    }
}
