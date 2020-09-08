<?php 

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'first_name' => $this->name,
      'last_name' => $this->lname,
      'country' => $this->whenLoaded('country', function(){
        return $this->country->name;
      }),
      'age' => $this->age,
      'value' => $this->value,
      'role' => $this->whenLoaded('role', function(){
        return $this->role->name;
      }),
      'team' => $this->whenLoaded('team', function(){
        return $this->team->name;
      })
    ];
  }
}