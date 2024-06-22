<?php

namespace App\Http\Resources;

use App\Models\Guild;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Guild
 */
class GuildDetailedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'icon_url' => $this->icon_url,
            'invite_code' => $this->invite_code,
            'channels' => $this->channels()->select('id', 'name')->get()->toArray(),
            'members' => $this->getMembers(),
        ];
    }

    protected function getMembers(): array
    {
        return $this->members->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'role' => $member->pivot->role,

            ];
        })->toArray();
    }
}
