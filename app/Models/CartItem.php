<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'selected'];

    public function product()
    {
        // Nếu bạn dùng bảng products trực tiếp
        return $this->belongsTo(Product::class);
        
        // Hoặc nếu bạn dùng bảng product_variants
        // return $this->belongsTo(ProductVariant::class, 'product_id');
    }
}