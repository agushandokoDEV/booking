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
        Schema::create('bookinglist_time', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bookinglist_id');
            $table->time('available_at');
            $table->enum('status', ['booked', 'available'])->default('available');
            $table->boolean('can_book')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookinglist_time');
    }
};
