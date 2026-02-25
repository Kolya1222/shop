<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommerceOrderProductsTable extends Migration
{
    public function up()
    {
        Schema::create('commerce_order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('product_id')->nullable()->index();
            $table->string('title', 255);
            $table->decimal('price', 16, 6);
            $table->float('count')->unsigned()->default(1);
            $table->text('options')->nullable();
            $table->text('meta')->nullable();
            $table->tinyInteger('position')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commerce_order_products');
    }
}
