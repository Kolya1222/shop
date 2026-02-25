<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommerceOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('commerce_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->nullable()->index();
            $table->string('name', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->decimal('amount', 16, 6);
            $table->string('currency', 8);
            $table->string('lang', 32);
            $table->text('fields')->nullable();
            $table->tinyInteger('status_id')->unsigned();
            $table->string('hash', 32)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commerce_orders');
    }
}
