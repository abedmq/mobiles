<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MobileApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('mobile_apps',function (Blueprint $table){
            $table->id();

            $table->string('token')->unique();
            $table->string('name')->nullable();
            $table->string('ip');
            $table->string('country_code');
            $table->string('mac')->nullable();
            $table->enum('type',['android','ios']);
            $table->string('firebase_token')->nullable();
            $table->timestamp('last_check')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->string('mobile')->nullable();

            $table->timestamps();
            $table->index(['token', 'created_at']);

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
        Schema::dropIfExists('mobile_apps');
    }
}
