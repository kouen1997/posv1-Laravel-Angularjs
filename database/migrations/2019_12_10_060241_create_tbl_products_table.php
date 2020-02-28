<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')
                            ->references('id')
                            ->on('tbl_users')
                            ->onDelete('cascade');

            $table->bigInteger('store_id')->nullable()->unsigned();
            $table->foreign('store_id')
                            ->references('id')
                            ->on('tbl_store')
                            ->onDelete('cascade');
                            
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->string('type')->nullable();

            $table->bigInteger('brand_id')->nullable()->unsigned();
            $table->foreign('brand_id')
                            ->references('id')
                            ->on('tbl_brand')
                            ->onDelete('cascade');

            $table->bigInteger('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')
                            ->references('id')
                            ->on('tbl_category')
                            ->onDelete('cascade');

            $table->bigInteger('child_id')->nullable()->unsigned();
            $table->foreign('child_id')
                            ->references('id')
                            ->on('tbl_category')
                            ->onDelete('cascade');

            $table->string('unit');
            $table->integer('qty');
            $table->double('cost', 10, 2)->nullable();
            $table->double('price', 10, 2);
            $table->tinyInteger('featured')->default(0);
            $table->double('promotional_price', 10, 2)->nullable();
            $table->date('promotional_start')->nullable();
            $table->date('promotional_end')->nullable();
            $table->string('tax_method')->nullable();
            $table->string('tax')->nullable();
            $table->text('details')->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('tbl_products');
    }
}
