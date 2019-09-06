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
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->unsigned();
            $table->bigInteger('process_id')->unsigned()->nullable();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('set null');
            //$table->bigInteger('customer_id')->unsigned();
            //$table->foreign('customer_id')->references('id')->on('customers');
            $table->bigInteger('contract_id')->unsigned();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->bigInteger('creating_user_id')->unsigned()->nullable();
            $table->foreign('creating_user_id')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('closing_user_id')->unsigned()->nullable();
            $table->foreign('closing_user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('process_name');
            $table->string('process_slug');
            $table->string('creating_user_name');
            $table->string('creating_user_email');
            $table->string('closing_user_name')->nullable();
            $table->string('closing_user_email')->nullable();
            $table->date('deadline');
            $table->timestamps();
            
            $table->unique(['task', 'task_sequence'], 'tasks_task_task_seq_index');
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
            //$table->dropForeign('tasks_customer_id_foreign');
            $table->dropForeign('tasks_contract_id_foreign');
            $table->dropForeign('tasks_creating_user_id_foreign');
            $table->dropForeign('tasks_closing_user_id_foreign');
        });
        Schema::dropIfExists('tasks');
        //DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
