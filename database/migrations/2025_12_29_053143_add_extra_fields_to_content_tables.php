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
        // Bổ sung cho bảng pages
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('status', ['publish', 'pending', 'draft'])->default('draft')->after('content');
            $table->foreignId('user_id')->nullable()->constrained()->after('status');
        });

        // Bổ sung cho bảng posts
        Schema::table('posts', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('title'); // Để chatbot tóm tắt nhanh
            $table->enum('status', ['publish', 'pending', 'trash'])->default('pending')->after('content');
            $table->foreignId('user_id')->nullable()->constrained()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_tables', function (Blueprint $table) {
            //
        });
    }
};
