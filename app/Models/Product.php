<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'catalog_type', 'name', 'slug', 'description', 'price', 'image_path', 'is_available', 'is_featured', 'catalog_attributes'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'catalog_attributes' => 'array',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        $imagePath = trim((string) ($this->image_path ?? ''));

        if ($imagePath === '') {
            return asset('assets/img/logo/coffee-cup.png');
        }

        if (preg_match('#^https?://#i', $imagePath) === 1) {
            return $imagePath;
        }

        if (str_starts_with($imagePath, '/')) {
            $imagePath = ltrim($imagePath, '/');
        }

        $baseUrl = trim((string) (config('filesystems.disks.s3.url') ?: env('AWS_URL', config('app.url', ''))));

        if ($baseUrl !== '') {
            $baseUrlPath = parse_url($baseUrl, PHP_URL_PATH) ?: $baseUrl;
            $baseUrlFile = pathinfo($baseUrlPath, PATHINFO_BASENAME);
            $imageFile = pathinfo($imagePath, PATHINFO_BASENAME);

            if ($baseUrlFile !== '' && $baseUrlFile === $imageFile) {
                return $baseUrl;
            }

            return rtrim($baseUrl, '/').'/'.ltrim($imagePath, '/');
        }

        if (str_starts_with($imagePath, 'assets/')) {
            return asset($imagePath);
        }

        if (preg_match('#^(menu|products)/#', $imagePath) === 1) {
            return asset('assets/img/'.$imagePath);
        }

        return asset($imagePath);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
