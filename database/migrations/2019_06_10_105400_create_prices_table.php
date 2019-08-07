<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('is_material')->default(false);
            $table->string('name');
            $table->text('description')->nullable();
            $table->bigInteger('sku');
            $table->decimal('price', 10, 2);
            $table->integer('status')->unsigned()->nullable();
            $table->timestamps();
            
            $table->unique(['sku', 'status'], 'prices_sku_status_index');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
