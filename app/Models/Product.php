<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'products';

    /**
     * Các trường có thể lưu hàng loạt (Mass Assignment)
     */
    protected $fillable = [
        'product_category_id', // Khớp với DB của bạn
        'name',
        'sku',
        'slug',
        'summary',
        'description',
        'brand',
        'featured_image',     // Khớp với DB của bạn thay vì thumbnail
        'meta_title',
        'meta_description',
        'status',
    ];

    /**
     * Quan hệ với Danh mục sản phẩm
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    protected static function booted()
    {
        // Khi Product bị xóa mềm (delete)
        static::deleted(function ($product) {
            // Nếu là xóa vĩnh viễn (forceDelete)
            if ($product->isForceDeleting()) {
                $product->variants()->forceDelete();
            } else {
                // Xóa mềm các con
                $product->variants()->delete();
            }
        });

        // Khi Product được khôi phục (restore)
        static::restored(function ($product) {
            $product->variants()->restore();
        });
    }

    /**
     * Quan hệ với các Biến thể sản phẩm (Variants)
     * Một sản phẩm gốc sẽ có nhiều biến thể dựa trên màu sắc, kích cỡ...
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * Scope để lấy các sản phẩm đang hiển thị (Active)
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
