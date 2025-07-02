<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string|null $image
 * @property string|null $image_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Product extends Model
{
    /**
     * Define the fillable properties.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    /**
     * Get the composed image url attribute.
     *
     * @return Attribute
     */
    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? config('app.url') . '/' . $this->image : null
        );
    }
}
