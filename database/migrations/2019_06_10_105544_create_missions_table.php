<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('route_id')->unsigned();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->string('name');
            $table->tinyInteger('sequence')->unsigned()->nullable();
            $table->integer('is_super')->unsigned()->nullable();
            $table->integer('is_final')->unsigned()->nullable();
            $table->timestamps();
            
            $table->unique(['route_id', 'sequence'], 'missions_route_id_sequence_index');
            $table->unique(['route_id', 'is_super'], 'missions_route_id_is_super_index');
            $table->unique(['route_id', 'is_final'], 'missions_route_id_is_final_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropForeign('missions_route_id_foreign');
        });
        Schema::dropIfExists('missions');
    }
}
