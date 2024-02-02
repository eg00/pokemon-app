<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\AbilityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name_en
 * @property string $name_jp
 * @property string $image
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 *
 * @method static Ability|null find($id, $columns = ['*'])
 * @method static AbilityFactory factory(...$parameters)
 */
class Ability extends Model
{
    use HasFactory;

    public const IMAGE_PATH = '/images/abilities';

    public const IMAGE_DISK = 'public';

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected static function newFactory(): AbilityFactory
    {
        return AbilityFactory::new();
    }
}
