<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Bổ sung cho bảng pages
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'status')) {
                $table->enum('status', ['publish', 'pending', 'draft'])->default('draft')->after('content');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('status');
            }
        });

        // 2. Bổ sung cho bảng post_categories
        Schema::table('post_categories', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('slug');
            $table->unsignedBigInteger('parent_id')->default(0)->after('status'); // Thêm parent_id để phân cấp như bảng cũ của bạn
        });

        // 3. Bổ sung cho bảng posts
        Schema::table('posts', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('title'); // Để chatbot tóm tắt nhanh
            $table->enum('status', ['publish', 'pending', 'trash'])->default('pending')->after('content');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('status');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Hàm này dùng để quay lại trạng thái cũ nếu bạn chạy lệnh rollback
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['status', 'user_id']);
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropColumn(['status', 'parent_id']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['excerpt', 'status', 'user_id']);
        });
    }
};
