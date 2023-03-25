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
        Schema::create('booking', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bookinglisttime_id');
            $table->string('booking_name')->nullable();
            $table->string('email');
            $table->string('token')->nullable();
            $table->enum('confirm', ['Y', 'N'])->default('N');
            $table->dateTime('token_exp')->nullable();
            $table->longText('ket')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking');
    }
};
