<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Xóa cột cũ trong bảng product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            // Kiểm tra xem cột có tồn tại không trước khi xóa để tránh lỗi
            if (Schema::hasColumn('product_variants', 'color')) {
                $table->dropColumn(['color', 'size']);
            }
        });

        // 2. Tạo bảng định nghĩa thuộc tính (RAM, Màu, Chất liệu...)
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->timestamps();
        });

        // 3. Tạo bảng giá trị thuộc tính (8GB, Đỏ, Cotton...)
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->string('value'); 
            $table->timestamps();
        });

        // 4. Tạo bảng trung gian để kết nối Biến thể với Thuộc tính
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');
            // Không cần timestamps cho bảng trung gian này trừ khi bạn muốn quản lý sâu hơn
        });
    }

    public function down()
    {
        // Phục hồi lại nếu lỡ rollback
        Schema::dropIfExists('variant_attribute_values');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('color')->nullable();
            $table->string('size')->nullable();
        });
    }
};