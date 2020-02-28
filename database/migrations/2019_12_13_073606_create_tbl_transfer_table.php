<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transfer', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('reference_code')->unique();
            $table->date('date');
            $table->enum('status', ['COMPLETE', 'PENDING', 'SENT'])->nullable();
            $table->bigInteger('from_store_id')->nullable()->unsigned();
            $table->foreign('from_store_id')
                            ->references('id')
                            ->on('tbl_store')
                            ->onDelete('cascade');
            $table->bigInteger('to_store_id')->nullable()->unsigned();
            $table->foreign('to_store_id')
                            ->references('id')
                            ->on('tbl_store')
                            ->onDelete('cascade');
            $table->text('orders')->nullable();
            $table->double('shipping_cost', 10, 2)->nullable();
            $table->double('grand_total', 10, 2)->nullable();
            $table->text('attachment')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('tbl_transfer');
    }
}
