<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FacebookUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */

//    protected $connection = 'facebook';

    public function up()
    {
        //
        Schema::create('facebook_users', function (Blueprint $table) {
            $table->id();

            $table->string('country_code');
            $table->string('mobile');
            $table->string('mobile_local');
            $table->string('facebook_id')->unique();
            $table->string('facebook_custom_id')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('location')->nullable();
            $table->string('social_status')->nullable();
            $table->string('job')->nullable();
            $table->string('company')->nullable();
            $table->string('career')->nullable();
            $table->string('collage_name')->nullable();
            $table->string('email')->nullable();
            $table->string('graduate_year')->nullable();
            $table->string('birth_day')->nullable();
            $table->string('checked')->default(0);
            $table->integer('facebook_file_id')->default(0);


            $table->softDeletes();
            $table->timestamps();
            $table->index(['mobile','country_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::connection('facebook')->dropIfExists('users');
    }
}
