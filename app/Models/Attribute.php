<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name'];

    // Kết nối với các giá trị cụ thể (Ví dụ: Màu sắc có Đỏ, Xanh)
    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    // Kết nối với danh mục để biết thuộc tính này thuộc về danh mục nào
    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'category_attributes', 'attribute_id', 'category_id');
    }
}