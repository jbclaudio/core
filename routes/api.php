<?php

use Illuminate\Http\Request;
use Rapidez\Core\Http\Middleware\VerifyAdminToken;
use Rapidez\Core\Models\Attribute;
use Rapidez\Core\Models\OptionSwatch;
use Rapidez\Core\Models\Quote;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('attributes', function () {
        return Attribute::getCachedWhere(function ($attribute) {
            return $attribute['filter'] || $attribute['sorting'];
        });
    });

    Route::get('swatches', function () {
        return OptionSwatch::getCachedSwatchValues();
    });

    Route::get('cart/{quoteIdMaskOrCustomerToken}', function ($quoteIdMaskOrCustomerToken) {
        return Quote::where(function ($query) use ($quoteIdMaskOrCustomerToken) {
            $query->where('masked_id', $quoteIdMaskOrCustomerToken)
                  ->orWhere('token', $quoteIdMaskOrCustomerToken);
        })->firstOrFail();
    });

    Route::prefix('admin')->middleware(VerifyAdminToken::class)->group(function () {
        Route::get('cache/clear', fn() => Artisan::call('cache:clear'));
        Route::get('index/products', fn() => Artisan::call('rapidez:index'));
    });
});
