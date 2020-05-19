<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRdtLabResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rdt_lab_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rdt_applicant_id')->nullable();
            $table->string('lab_result_type')->nullable()->index();
            $table->softDeletes();
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
        Schema::dropIfExists('rdt_lab_results');
    }
}
