<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPnsToRdtApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rdt_applicants', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_pns')->after('occupation_type')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rdt_applicants', function (Blueprint $table) {
            $table->dropColumn(['is_pns']);
        });
    }
}
