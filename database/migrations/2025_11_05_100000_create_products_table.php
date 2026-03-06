<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('old_slug')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('brand')->nullable();
            $table->string('function_category')->nullable();
            $table->string('catalog')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            // features and specifications are added in a later migration
            $table->string('image_url')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('video_url')->nullable();
            $table->string('manual_url')->nullable();
            $table->string('datasheet_url')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->integer('stock_quantity')->default(0);
            $table->string('stock_status')->default('in_stock');
            $table->integer('min_order_quantity')->default(1);
            $table->json('tags')->nullable();
            $table->json('categories')->nullable();
            $table->json('related_products')->nullable();
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('color')->nullable();
            $table->string('material')->nullable();
            $table->string('warranty_period')->nullable();
            $table->string('manufacturer_country')->nullable();
            $table->string('origin')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->json('structured_data')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->integer('search_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->boolean('indexable')->default(true);
            $table->string('status')->default('draft');
            $table->string('visibility')->default('public');
            $table->boolean('featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->string('language')->default('en');
            $table->json('custom_fields')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
