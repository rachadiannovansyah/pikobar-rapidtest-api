<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->integer('depth')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('code_bps')->nullable()->unique();
            $table->string('code_kemendagri')->nullable()->unique();
            $table->string('code_dinkes')->nullable()->unique();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('meta')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('areas');
    }
}
