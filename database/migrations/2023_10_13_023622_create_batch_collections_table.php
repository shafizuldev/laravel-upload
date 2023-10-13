<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_collections', function (Blueprint $table) {
            $table->id();
            $table->string('batch_upload_id');
            $table->string('unique_key');
            $table->string('product_title');
            $table->text('product_description');
            $table->string('style');
            $table->string('sanmar_mainframe_color');
            $table->string('size');
            $table->string('color_name');
            $table->enum('status', ['complete','failed']);
            $table->decimal('piece_price', 9,2);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_collections');
    }
}
