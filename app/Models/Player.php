<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Faker;

class Player extends Model
{
    protected $guarded = [];

    const YOUNGEST = 18;
    const OLDEST = 40;
    const VALUE = 1000000;

    public static function generate($role)
    {
        $faker = Faker\Factory::create();
        $player = Player::create([
            'name' => $faker->firstName,
            'lname' => $faker->lastName,
            'country_id' => Country::inRandomOrder()->first()->getKey(),
            'age' => $faker->numberBetween($min = static::YOUNGEST, $max = static::OLDEST),
            'value' => static::VALUE,
            'role_id' => Role::where('name', $role)->first()->getKey(),
        ]);
        return $player;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
