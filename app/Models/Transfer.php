<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'player_id';
    public $incrementing = false;
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
