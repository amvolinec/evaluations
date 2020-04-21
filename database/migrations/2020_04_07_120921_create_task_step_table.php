<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskStepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step_task', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('task_id')->index();
            $table->unsignedBigInteger('step_id')->index();

            $table->timestamps();

            $table->foreign('task_id')->references('id')
                ->on('tasks')
                ->onDelete('cascade');

            $table->foreign('step_id')->references('id')
                ->on('steps')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('step_task');
    }
}
