<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommerceOrderHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('commerce_order_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('status_id')->index();
            $table->text('comment');
            $table->tinyInteger('notify')->unsigned()->default(1);
            $table->integer('user_id')->nullable()->index();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commerce_order_history');
    }
}
