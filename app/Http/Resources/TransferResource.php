<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource {
  public function toArray($request)
  {
    return [
      'player' => new PlayerResource($this->player),
      'price' => $this->price
    ];
  }
}