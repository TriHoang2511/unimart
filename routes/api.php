<?php
use App\Http\Controllers\Api\ChatbotAPIController;
use Illuminate\Support\Facades\Route;
// Group này để public cho AI gọi sang

    Route::get('/sync-data', [ChatbotAPIController::class, 'getSyncData']);
    Route::get('/products/{id}/variants', [ChatbotAPIController::class, 'getVariants']);
    Route::get('/orders/tracking', [ChatbotAPIController::class, 'trackOrder']);
