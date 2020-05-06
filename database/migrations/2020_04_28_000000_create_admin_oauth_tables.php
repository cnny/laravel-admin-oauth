<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminOauthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users_third_pf_bind', function (Blueprint $table) {

            $table->increments('id');
            $table->string('platform', 100);
            $table->integer('user_id', false, true);
            $table->string('third_user_id', 191);
            $table->timestamps();

            $table->unique(['platform', 'user_id', 'third_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users_third_pf_bind');
    }
}
