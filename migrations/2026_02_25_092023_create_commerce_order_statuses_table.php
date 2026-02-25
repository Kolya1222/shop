<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommerceOrderStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('commerce_order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('alias', 255)->nullable();
            $table->string('color', 6)->nullable();
            $table->tinyInteger('notify')->unsigned()->default(0);
            $table->tinyInteger('default')->unsigned()->default(0);
            $table->tinyInteger('canbepaid')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commerce_order_statuses');
    }
}
