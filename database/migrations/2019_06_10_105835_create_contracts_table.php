<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contract_num')->unsigned()->unique();
            //$table->integer('iteration')->unsigned()->default(1);
            //$table->integer('production')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->bigInteger('price_id')->unsigned()->nullable();
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('set null');
            $table->integer('is_final')->unsigned()->nullable();
            $table->timestamps();
            
            //$table->unique(['iteration', 'production', 'customer_id'], 'prices_iteration_production_customer_id_index');
        });
        
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign('contracts_customer_id_foreign');
            $table->dropForeign('contracts_price_id_foreign');
        });
        Schema::dropIfExists('contracts');
    }
}
