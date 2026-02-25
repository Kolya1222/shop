<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommerceOrderPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('commerce_order_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->index();
            $table->decimal('amount', 16, 6);
            $table->tinyInteger('paid')->unsigned()->default(0);
            $table->string('hash', 128)->index();
            $table->string('payment_method', 255)->nullable();
            $table->string('original_order_id', 255)->nullable()->index();
            $table->text('meta');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commerce_order_payments');
    }
}
