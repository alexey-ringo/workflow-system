<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 40)->index()->unique();
            $table->mediumtext('value');
            $table->boolean('serialized')->default(0);
            $table->boolean('set_webhook')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_settings');
    }
}
