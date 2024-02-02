<?php

namespace App\Models;

use App\Enums\Shape;
use Carbon\CarbonImmutable;
use Database\Factories\PokemonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string $image
 * @property Shape $shape
 * @property Location $location
 * @property Ability $ability
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 *
 * @method static Pokemon|null find($id, $columns = ['*'])
 * @method static PokemonFactory factory(...$parameters)
 */
class Pokemon extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'location_id', 'id');
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Ability::class,
            'pokemon_ability',
            'pokemon_id',
            'ability_id',
        );
    }

    public static function newFactory(): PokemonFactory
    {
        return PokemonFactory::new();
    }
}
