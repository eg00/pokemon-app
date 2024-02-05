<?php

namespace App\Models;

use App\Enums\Shape;
use Carbon\CarbonImmutable;
use Database\Factories\PokemonFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $image
 * @property Shape $shape
 * @property int $location_id
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property-read Collection<Ability> $abilities
 * @property-read Location $location
 *
 * @method static Pokemon|null find($id, $columns = ['*'])
 * @method static PokemonFactory factory(...$parameters)
 */
class Pokemon extends Model
{
    use HasFactory;

    public const IMAGE_PATH = '/images/pokemon';

    public const IMAGE_DISK = 'public';

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
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

    public function scopeFilteredByLocation(Builder $query, string $location): void
    {
        $query->whereHas(
            'location',
            fn (Builder $query) => $query->where('name', $location));
    }

    public static function newFactory(): PokemonFactory
    {
        return PokemonFactory::new();
    }
}
