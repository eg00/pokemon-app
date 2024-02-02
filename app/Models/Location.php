<?php

namespace App\Models;

use App\Enums\Region;
use Carbon\CarbonImmutable;
use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property Region $region
 * @property int|null $parent_id
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property-read Location|null $parent
 * @property-read Collection<Location> $children
 *
 * @method static Location|null find($id, $columns = ['*'])
 * @method static LocationFactory factory(...$parameters)
 */
class Location extends Model
{
    use HasFactory;

    protected $casts = [
        'region' => Region::class,
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /**
     * @var array<string>
     */
    protected $with = ['children'];

    public function parent(): HasOne
    {
        return $this->hasOne(Location::class, 'id', 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id', 'id');
    }

    protected static function newFactory(): LocationFactory
    {
        return LocationFactory::new();
    }
}
