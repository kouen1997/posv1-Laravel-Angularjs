<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_expense', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('code')->nullable()->unique();
            $table->bigInteger('category_id')->nullable()->unsigned();
            $table->foreign('category_id')
                            ->references('id')
                            ->on('tbl_expense_category')
                            ->onDelete('cascade');
            $table->bigInteger('store_id')->nullable()->unsigned();
            $table->foreign('store_id')
                            ->references('id')
                            ->on('tbl_store')
                            ->onDelete('cascade');
            $table->double('amount', 10, 2)->nullable();
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
        Schema::dropIfExists('tbl_expense');
    }
}
