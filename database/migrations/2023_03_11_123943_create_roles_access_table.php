<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_access', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('role_id');
            $table->foreignUuid('menu_id');
            $table->string('menu_code',50);
            $table->boolean('allowed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::table('roles_access', function ($table) {
        //     $table->foreign('role_id')->references('id')->on('roles');
        //     $table->foreign('menu_id')->references('id')->on('menu');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_access');
    }
};
