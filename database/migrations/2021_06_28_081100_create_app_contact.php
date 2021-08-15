<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_contact', function (Blueprint $table) {
            $table->id();
            $table->integer('mobile_app_id');
            $table->string('mobile');
            $table->string('full_mobile');
            $table->string('country_code');
            $table->string('name');
            $table->timestamps();
            $table->index(['mobile', 'country_code','full_mobile']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_contact');
    }
}
