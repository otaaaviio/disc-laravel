<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 *
 * @property-read Guild|null $guild
 * @property-read User|null $user
 * @method static Builder|GuildMember newModelQuery()
 * @method static Builder|GuildMember newQuery()
 * @method static Builder|GuildMember query()
 * @mixin \Eloquent
 */
class GuildMember extends Pivot
{
    protected $fillable = [
        'user_id',
        'guild_id',
        'nickname',
        'joined_at',
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
