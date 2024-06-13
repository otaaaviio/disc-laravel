<?php

namespace App\Models;

use Database\Factories\GuildFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $icon_url
 * @property string|null $invite_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Channel> $channels
 * @property-read int|null $channels_count
 * @property-read Collection<int, User> $members
 * @property-read int|null $members_count
 * @method static GuildFactory factory($count = null, $state = [])
 * @method static Builder|Guild newModelQuery()
 * @method static Builder|Guild newQuery()
 * @method static Builder|Guild onlyTrashed()
 * @method static Builder|Guild query()
 * @method static Builder|Guild whereCreatedAt($value)
 * @method static Builder|Guild whereDeletedAt($value)
 * @method static Builder|Guild whereDescription($value)
 * @method static Builder|Guild whereIconUrl($value)
 * @method static Builder|Guild whereId($value)
 * @method static Builder|Guild whereInviteLink($value)
 * @method static Builder|Guild whereName($value)
 * @method static Builder|Guild whereUpdatedAt($value)
 * @method static Builder|Guild withTrashed()
 * @method static Builder|Guild withoutTrashed()
 * @method static whereHas(string $relation, \Closure $query)
 * @method static Builder|Guild whereInviteCode($value)
 * @method static where(string $column, mixed $value)
 * @method static create(array $data)
 * @mixin \Eloquent
 */
class Guild extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'icon_url',
        'invite_code',
    ];

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'guild_members')
            ->using(GuildMember::class)
            ->withPivot('role');
    }
}
