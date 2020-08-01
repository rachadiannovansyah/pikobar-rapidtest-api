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
            $table->integer('depth')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('parent_code_kemendagri')->nullable()->index();
            $table->string('code_kemendagri')->nullable()->unique();
            $table->string('code_bps')->nullable()->unique();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('meta')->nullable();
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
