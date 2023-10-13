<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('document_checksum');
            $table->datetime('completed_at')->nullable();
            $table->integer('total_record')->nullable();
            $table->integer('total_success')->nullable();
            $table->integer('total_failed')->nullable();
            $table->enum('status', ['complete', 'pending', 'processing','failed']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_uploads');
    }
}
