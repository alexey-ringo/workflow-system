<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('task')->unsigned();
            $table->integer('sequence')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->integer('status')->unsigned();
            $table->bigInteger('mission_id')->unsigned()->nullable();
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('set null');
            $table->bigInteger('creating_user_id')->unsigned()->nullable();
            $table->foreign('creating_user_id')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('closing_user_id')->unsigned()->nullable();
            $table->foreign('closing_user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('mission_name');
            $table->string('creating_user_name');
            $table->string('creating_user_email');
            $table->string('closing_user_name')->nullable();
            $table->string('closing_user_email')->nullable();
            $table->date('deadline');
            $table->timestamps();
            
            $table->unique(['task', 'sequence', 'status'], 'task_seq_stat_tasks_index');
            //$table->primary(['id', 'task', 'sequence']);
        });
        
        //DB::statement('ALTER TABLE tasks MODIFY id INTEGER NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_mission_id_foreign');
            $table->dropForeign('tasks_creating_user_id_foreign');
            $table->dropForeign('tasks_closing_user_id_foreign');
        });
        Schema::dropIfExists('tasks');
        //DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
