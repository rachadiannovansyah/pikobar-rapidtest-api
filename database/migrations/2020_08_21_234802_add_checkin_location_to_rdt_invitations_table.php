<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckinLocationToRdtInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rdt_invitations', function (Blueprint $table) {
            $table->string('attend_location')->after('rdt_event_schedule_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rdt_invitations', function (Blueprint $table) {
            $table->dropColumn(['attend_location']);
        });
    }
}
