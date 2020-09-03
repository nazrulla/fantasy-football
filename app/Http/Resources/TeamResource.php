<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'name' => $this->name,
      'country' => $this->country->name,
      'team_value' => $this->value,
      'budget' => $this->budget,
      'players' => PlayerResource::collection($this->whenLoaded('players')),
    ];
  }
}
