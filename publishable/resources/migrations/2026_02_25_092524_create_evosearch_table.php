<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvosearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('evosearch_table', function (Blueprint $table) {
            $table->id();
            $table->integer('docid')->index();
            $table->string('table', 255)->index();
            $table->string('pagetitle', 255)->index();
            $table->mediumText('content_with_tv')->index();
            $table->mediumText('content_with_tv_index')->index();

            // Полнотекстовые индексы для поиска
            $table->fullText('pagetitle');
            $table->fullText('content_with_tv');
            $table->fullText('content_with_tv_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evosearch_table');
    }
}
