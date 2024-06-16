<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Database\Factories\ChannelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $guild_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Guild $guild
 * @property-read Collection<int, Message> $messages
 * @property-read int|null $messages_count
 * @method static ChannelFactory factory($count = null, $state = [])
 * @method static Builder|Channel newModelQuery()
 * @method static Builder|Channel newQuery()
 * @method static Builder|Channel onlyTrashed()
 * @method static Builder|Channel query()
 * @method static Builder|Channel whereCreatedAt($value)
 * @method static Builder|Channel whereDeletedAt($value)
 * @method static Builder|Channel whereDescription($value)
 * @method static Builder|Channel whereGuildId($value)
 * @method static Builder|Channel whereId($value)
 * @method static Builder|Channel whereName($value)
 * @method static Builder|Channel whereUpdatedAt($value)
 * @method static Builder|Channel withTrashed()
 * @method static Builder|Channel withoutTrashed()
 * @method static create(array $data)
 * @mixin Eloquent
 */
class Channel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'guild_id',
    ];

    public function guild(): BelongsTo
    {
        return $this->belongsTo(Guild::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
