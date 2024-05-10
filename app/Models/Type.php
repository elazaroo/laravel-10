<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Type extends Model
{
    protected $fillable = ['name'];

    use HasFactory;

    public function pokemons()
    {
        return $this->belongsToMany(Pokemons::class, 'type_pokemon');
    }
}
