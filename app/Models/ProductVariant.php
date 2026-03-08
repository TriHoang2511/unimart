<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        // 'slug',
        'technical_specifications',
        'description',
        'variant_full_name',
        'price',
        'compare_at_price',
        'status',
        'availability',
        'stock_qty',
        'variant_image',
    ];

    protected $casts = [
        'price' => 'float',
        'compare_at_price' => 'float',
        'stock_qty' => 'integer',
    ];

    /**
     * Quan hệ ngược lại với Sản phẩm gốc
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * QUAN TRỌNG: Quan hệ với Attribute Values (Dành cho EAV)
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attribute_values', 'variant_id', 'attribute_value_id');
    }

    public function getDisplayDescriptionAttribute()
    {
        if (!empty(trim($this->description))) {
            return $this->description;
        }

        return optional($this->product)->description;
    }

    public function getDisplaySpecsAttribute()
    {
        if (!empty(trim($this->technical_specifications))) {
            return $this->technical_specifications;
        }

        return optional($this->product)->technical_specifications;
    }
}
