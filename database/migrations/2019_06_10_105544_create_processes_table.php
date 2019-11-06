<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('route_id')->unsigned();
            $table->foreign('route_id')->references('id')->on('routes');
            $table->string('title');            
            $table->integer('deadline')->unsigned();
            $table->tinyInteger('sequence')->unsigned()/*->nullable()*/;
            $table->integer('is_super')->unsigned()->nullable();
            $table->integer('is_final')->unsigned()->nullable();
            $table->integer('is_active')->unsigned()->nullable();
            $table->timestamps();
            
            $table->unique(['route_id', 'sequence', 'is_active'], 'processes_route_id_sequence_index');
            $table->unique(['route_id', 'is_super'], 'processes_route_id_is_super_index');
            $table->unique(['route_id', 'is_final'], 'processes_route_id_is_final_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->dropForeign('processes_route_id_foreign');
        });
        Schema::dropIfExists('processes');
    }
}
