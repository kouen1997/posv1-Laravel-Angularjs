<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_purchase', function (Blueprint $table) {
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

            $table->bigInteger('coupon_id')->nullable()->unsigned();
            $table->foreign('coupon_id')
                            ->references('id')
                            ->on('tbl_coupon');
                            
            $table->string('invoice_id')->unique();
            $table->text('orders')->nullable();
            $table->double('cash_payment', 10, 2)->nullable();
            $table->integer('grand_items')->nullable();
            $table->double('grand_tax', 10, 2)->nullable();
            $table->double('grand_total', 10, 2)->nullable();
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
        Schema::dropIfExists('tbl_purchase');
    }
}
