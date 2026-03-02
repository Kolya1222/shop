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
            $table->mediumText('content_with_tv');
            $table->mediumText('content_with_tv_index');
            $table->fullText('pagetitle', 'evosearch_pagetitle_fulltext');
            $table->fullText(['content_with_tv', 'content_with_tv_index'], 'evosearch_content_combo_fulltext');
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
