<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scenarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('group_scenario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scenario_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->timestamps();

            $table->foreign('scenario_id')->references('id')
                ->on('scenarios')
                ->onDelete('set null');

            $table->foreign('group_id')->references('id')
                ->on('groups')
                ->onDelete('set null');
        });

        Schema::create('step_scenario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('scenario_id')->nullable();
            $table->unsignedBigInteger('step_id')->nullable();
            $table->timestamps();

            $table->foreign('scenario_id')->references('id')
                ->on('scenarios')
                ->onDelete('set null');

            $table->foreign('step_id')->references('id')
                ->on('steps')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_scenario');
        Schema::dropIfExists('step_scenario');
        Schema::dropIfExists('scenarios');
    }
}
