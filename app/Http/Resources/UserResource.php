<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'username' => $this->username,
      'email' => $this->email,
      'team' => new TeamResource($this->team),
    ];
  }
}
