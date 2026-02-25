<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('list_value_table', function (Blueprint $table) {
            $table->id();
            $table->integer('parent')->default(0);
            $table->string('title', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('sort')->default(0);
            $table->index('parent');
            $table->index('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_value_table');
    }
}
