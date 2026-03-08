<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Chỉ định bảng (nếu tên bảng của bạn là 'customers' thì Laravel tự hiểu, 
    // nhưng khai báo tường minh sẽ tốt hơn)
    protected $table = 'customers';

    // Các trường cho phép lưu dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'address'
    ];

    /**
     * Quan hệ: Một khách hàng có thể có nhiều đơn hàng.
     * Giúp AI hoặc Admin tra cứu: $customer->orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    /**
     * Helper: Lấy đơn hàng mới nhất của khách
     */
    public function latestOrder()
    {
        return $this->hasOne(Order::class, 'customer_id')->latestOfMany();
    }
}