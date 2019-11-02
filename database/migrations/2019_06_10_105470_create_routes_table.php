<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();            
            $table->integer('value')->unsigned();
            $table->text('description')->nullable();
            $table->integer('is_active')->unsigned()->nullable();
            $table->timestamps();
            
            $table->unique(['value', 'is_active'], 'routes_value_is_active_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
