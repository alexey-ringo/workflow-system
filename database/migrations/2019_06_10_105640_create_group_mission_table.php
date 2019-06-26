<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_mission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_id')->unsigned()->default(1);
            $table->foreign('group_id')->references('id')->on('groups');
            
            $table->bigInteger('mission_id')->unsigned()->default(1);
            $table->foreign('mission_id')->references('id')->on('missions');
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
        Schema::table('group_mission', function (Blueprint $table) {
            $table->dropForeign('group_mission_group_id_foreign');
            $table->dropForeign('group_mission_mission_id_foreign');
        });
        Schema::dropIfExists('group_mission');
    }
}
