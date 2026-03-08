<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // user_id để trống (nullable) nếu khách chưa đăng nhập
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            // session_token để nhận diện khách vãng lai qua Cookie
            $table->string('session_token')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};