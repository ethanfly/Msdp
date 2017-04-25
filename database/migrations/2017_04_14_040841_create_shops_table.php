<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('manager')->nullable();
            $table->string('number')->nullable();
            $table->integer('market_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->timestamps();
            $table->foreign('market_id')
                ->references('id')->on('markets')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('type_id')
                ->references('id')->on('types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
