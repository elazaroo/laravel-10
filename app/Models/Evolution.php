<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evolution extends Model
{
    use HasFactory;

    protected $fillable = ['family', 'pokemons_id', 'position'];

    public function evoDe(): BelongsTo
    {
        return $this->belongsTo(Pokemons::class);
    }
}
