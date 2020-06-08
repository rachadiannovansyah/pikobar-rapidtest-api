<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRdtSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rdt_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rdt_applicant_id')->nullable();
            $table->string('registration_code', 10)->unique();
            $table->boolean('invited')->nullable()->index();
            $table->boolean('attended')->nullable()->index();
            $table->boolean('interested')->nullable()->index();
            $table->tinyInteger('test_method')->nullable()->index();
            $table->timestamps();

            $table->foreign('rdt_applicant_id')
                ->references('id')
                ->on('rdt_applicants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rdt_surveys');
    }
}
