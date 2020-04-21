<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',191);
            $table->unsignedInteger('time')->default(60);
            $table->unsignedBigInteger('evaluation_id');
            $table->unsignedBigInteger('step_id')->default(0)->index();
            $table->unsignedBigInteger('group_id')->default(0)->index();
            $table->timestamps();

            $table->foreign('evaluation_id')->references('id')->on('evaluations')
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
        Schema::dropIfExists('items');
    }
}
