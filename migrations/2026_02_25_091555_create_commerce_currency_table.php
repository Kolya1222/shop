<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommerceCurrencyTable extends Migration
{
    public function up()
    {
        Schema::create('commerce_currency', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('code', 8)->index();
            $table->float('value')->default(1);
            $table->string('left', 32)->nullable();
            $table->string('right', 32)->nullable();
            $table->tinyInteger('decimals')->unsigned()->default(2);
            $table->string('decsep', 8);
            $table->string('thsep', 8);
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->tinyInteger('default')->unsigned()->default(0);
            $table->string('lang', 8)->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('commerce_currency');
    }
}
