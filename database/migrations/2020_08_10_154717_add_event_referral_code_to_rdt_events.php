<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventReferralCodeToRdtEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rdt_events', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->after('host_name')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rdt_events', function (Blueprint $table) {
            $table->dropColumn(['referral_code']);
        });
    }
}
