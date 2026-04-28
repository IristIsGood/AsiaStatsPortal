<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataPointController;
use App\Http\Controllers\Api\DataValidationController;
use App\Http\Controllers\Api\IndicatorController;
use App\Http\Controllers\Api\RegionController;

Route::prefix('v1')->group(function () {

    // ── 公开路由（无需登录）──────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
    });

    Route::apiResource('indicators', IndicatorController::class)
        ->only(['index', 'show']);

    Route::apiResource('regions', RegionController::class)
        ->only(['index', 'show']);

    Route::get('data-points', [DataPointController::class, 'index']);

    // ── 需要登录的路由 ───────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Editor 以上才能新增/修改/删除
        Route::apiResource('indicators', IndicatorController::class)
            ->except(['index', 'show']);

        Route::apiResource('regions', RegionController::class)
            ->except(['index', 'show']);

        Route::apiResource('data-points', DataPointController::class)
            ->except(['index']);

        // 数据验证（需要登录）
        Route::prefix('validations')->group(function () {
            Route::get('pending',           [DataValidationController::class, 'pending']);
            Route::post('{id}/approve',     [DataValidationController::class, 'approve']);
            Route::post('{id}/reject',      [DataValidationController::class, 'reject']);
        });
    });
});