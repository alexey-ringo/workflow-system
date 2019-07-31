<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('task_num')->unsigned()->index();
            $table->integer('task_seq_num')->unsigned();
            $table->bigInteger('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->integer('comment_seq')->unsigned();
            $table->integer('common_comment_seq')->unsigned();
            $table->text('comment');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('user_name');
            $table->string('user_email');
            $table->timestamps();
            
            $table->unique(['task_id', 'comment_seq'], 'task_comment_seq_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_task_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
            
        });
        Schema::dropIfExists('comments');
    }
}
