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
            $table->bigInteger('task')->unsigned()->index();
            $table->integer('task_sequence')->unsigned();
            $table->integer('route')->unsigned();
            $table->integer('process_sequence')->unsigned();
            $table->integer('is_contractable')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->integer('status')->unsigned();
            $table->bigInteger('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes');
            $table->bigInteger('contract_id')->unsigned();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->bigInteger('tariff_id')->unsigned()->nullable();
            $table->bigInteger('creating_user_id')->unsigned()->nullable();
            $table->foreign('creating_user_id')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('closing_user_id')->unsigned()->nullable();
            $table->foreign('closing_user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('creating_user_name');
            $table->string('creating_user_email');
            $table->string('closing_user_name')->nullable();
            $table->string('closing_user_email')->nullable();
            $table->integer('is_expiried')->unsigned()->nullable();
            $table->timestamps();
            
            $table->unique(['task', 'task_sequence'], 'tasks_task_task_sequence_index');
            $table->unique(['is_contractable', 'contract_id', 'task_sequence', 'process_sequence',], 'tasks_unique_contractable_task_index');
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
            $table->dropForeign('tasks_process_id_foreign');
            $table->dropForeign('tasks_contract_id_foreign');
            $table->dropForeign('tasks_creating_user_id_foreign');
            $table->dropForeign('tasks_closing_user_id_foreign');
        });
        Schema::dropIfExists('tasks');
        //DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
