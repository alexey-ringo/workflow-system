<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_id')->unsigned()->default(1);
            $table->foreign('group_id')->references('id')->on('groups');
            
            $table->bigInteger('process_id')->unsigned()->default(1);
            $table->foreign('process_id')->references('id')->on('processes');
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
        Schema::table('group_process', function (Blueprint $table) {
            $table->dropForeign('group_process_group_id_foreign');
            $table->dropForeign('group_process_process_id_foreign');
        });
        Schema::dropIfExists('group_process');
    }
}
