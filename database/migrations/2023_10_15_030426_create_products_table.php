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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('unique_key')->unique();
            $table->string('product_title');
            $table->text('product_description');
            $table->string('style');#
            $table->string('sanmar_mainframe_color');
            $table->string('size');
            $table->string('color_name');
            $table->decimal('piece_price');
            $table->timestampsTz();
            $table->softDeletesTz();

            // "available_sizes",
            // "brand_logo_image",
            // "thumbnail_image",
            // "color_swatch_image",
            // "product_image",
            // "spec_sheet",
            // "price_text",
            // "suggested_price",
            // "category_name",
            // "subcategory_name",
            // "color_square_image",
            // "color_product_image",
            // "color_product_image_thumbnail",
            // "qty",
            // "piece_weight",
            // "dozens_price",
            // "case_price",
            // "price_group",
            // "case_size",
            // "inventory_key",
            // "size_index",
            // "mill",
            // "product_status",
            // "companion_styles",
            // "msrp",
            // "map_pricing",
            // "front_model_image_url",
            // "back_model_image",
            // "front_flat_image",
            // "back_flat_image",
            // "product_measurements",
            // "pms_color",
            // "gtin",
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
