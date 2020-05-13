<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRdtApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rdt_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code', 10)->unique();
            $table->unsignedBigInteger('rdt_event_id')->nullable();
            $table->string('nik', 20)->nullable()->index();
            $table->string('name')->index();
            $table->string('email')->nullable();
            $table->string('phone_number')->index()->nullable();
            $table->string('gender', 5)->nullable()->index();
            $table->date('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->string('province_code')->nullable();
            $table->string('city_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('village_code')->nullable();
            $table->tinyInteger('occupation_type')->nullable();
            $table->string('occupation_name')->nullable();
            $table->string('workplace_name')->nullable();
            $table->string('symptoms')->nullable();
            $table->text('symptoms_notes')->nullable();
            $table->tinyInteger('symptoms_interaction')->nullable();
            $table->string('symptoms_activity')->nullable();
            $table->string('file_reference')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->string('status', 20)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('rdt_event_id')
                ->references('id')
                ->on('rdt_events');

            $table->foreign('province_code')
                ->references('code_kemendagri')
                ->on('areas');

            $table->foreign('city_code')
                ->references('code_kemendagri')
                ->on('areas');

            $table->foreign('district_code')
                ->references('code_kemendagri')
                ->on('areas');

            $table->foreign('village_code')
                ->references('code_kemendagri')
                ->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rdt_applicants');
    }
}
