<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Cột này sẽ dùng để hiển thị trực tiếp ra trang chủ/danh mục
            $table->string('variant_full_name')->nullable()->after('product_id');

            // Thêm index để tìm kiếm (Search) nhanh hơn nếu cần
            $table->index('variant_full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            //
        });
    }
};
