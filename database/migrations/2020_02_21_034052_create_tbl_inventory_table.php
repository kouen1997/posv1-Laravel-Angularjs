<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inventory', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')
                            ->references('id')
                            ->on('tbl_users');

            $table->bigInteger('store_id')->nullable()->unsigned();
            $table->foreign('store_id')
                            ->references('id')
                            ->on('tbl_store');

            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')
                            ->references('id')
                            ->on('tbl_products');

            $table->string('inventory_id');
            $table->integer('qty');
            $table->enum('status', ['IN', 'OUT', 'PURCHASE', 'TRANSFER', 'RETURN'])->nullable();
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
        Schema::dropIfExists('tbl_inventory');
    }
}
