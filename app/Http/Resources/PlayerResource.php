<?php 

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource{
  public function toArray($request)
  {
    return [
      'first_name' => $this->name,
      'last_name' => $this->lname,
      'country' => $this->country->name,
      'age' => $this->age,
      'value' => $this->value,
      'role' => $this->role->name,
    ];
  }
}