<?php

namespace App\Models;

use App\Enums\Region;
use Carbon\CarbonImmutable;
use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property Region $region
 * @property int|null $parent_id
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
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

    protected static function newFactory(): LocationFactory
    {
        return LocationFactory::new();
    }
}
