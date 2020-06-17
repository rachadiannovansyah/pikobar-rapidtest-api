<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRdtInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rdt_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rdt_applicant_id')->nullable();
            $table->string('registration_code', 10)->unique();
            $table->unsignedBigInteger('rdt_event_id')->nullable();
            $table->string('test_type')->nullable()->index();
            $table->string('lab_result_type')->nullable()->index();
            $table->string('confirm_status')->nullable()->index();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->timestamp('result_at')->nullable();
            $table->timestamps();

            $table->foreign('rdt_event_id')
                ->references('id')
                ->on('rdt_events');

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
        Schema::dropIfExists('rdt_invitations');
    }
}
