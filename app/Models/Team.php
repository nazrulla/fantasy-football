<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = ['value'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function updateValue()
    {
        $value = $this->players()->sum('value');
        $this->forceFill(['value' => $value]);
        $this->save();
    }
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
