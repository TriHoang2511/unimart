<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = ['attribute_id', 'value', 'value_code', 'is_active'];

    // Trỏ ngược về thuộc tính cha
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    // Kết nối với các biến thể sản phẩm sử dụng giá trị này
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'variant_attribute_values', 'attribute_value_id', 'variant_id');
    }
}