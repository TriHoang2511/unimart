<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'sort_order',
        'status',
        'thumbnail',
        'description',
    ];

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    // Kết nối với thuộc tính để biết danh mục này có những thuộc tính nào
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes', 'category_id', 'attribute_id');
    }

    /**
     * Một danh mục có nhiều sản phẩm
     */
    public function products()
    {
        // product_category_id là tên cột khóa ngoại ở bảng products
        return $this->hasMany(Product::class, 'product_category_id');
    }

    //  KẾT THỨC PRODUCT CATEGORY CHO ADMIN ==============================
    public function childrenForMenu()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->with('childrenForMenu');
    }

    /**
     * Lấy toàn bộ ID danh mục con (n cấp)
     */
    public function getAllChildrenIds()
    {
        $ids = collect([$this->id]);

        foreach ($this->children as $child) {
            $ids = $ids->merge($child->getAllChildrenIds());
        }

        return $ids;
    }
}
