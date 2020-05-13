<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('province_code')->nullable();
            $table->string('city_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('village_code')->nullable();
            $table->string('status', 20)->nullable()->index();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('users');
    }
}
