<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('version')->nullable()->default(1);

			$table->string('name')->nullable();
			$table->string('description')->nullable();
			$table->unsignedInteger('time')->nullable();
			$table->boolean('selected')->nullable();

            $table->unsignedBigInteger('evaluation_id')->nullable();
			$table->unsignedBigInteger('group_id')->nullable();
			$table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('evaluation_id')->references('id')->on('evaluations')->onDelete('set null');
			$table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisions');
    }
}
