<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pokemons extends Model
{
    protected $fillable = ['name', 'image'];

    use HasFactory;

    public function types()
    {
        return $this->belongsToMany(Type::class, 'type_pokemon');
    }

    public function evoA(): HasMany
    {
        return $this->hasMany(Evolution::class);
    }
}
