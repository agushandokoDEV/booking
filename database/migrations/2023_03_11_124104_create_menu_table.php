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
        Schema::create('menu', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            // $table->string('resource', 50);
            // $table->string('access', 50)->nullable();
            $table->string('code',50);
            $table->string('url')->nullable();
            $table->integer('sorting');
            $table->integer('parent_id')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_menu')->default(false);
            // $table->enum('active', ['active', 'pending', 'banned'])->default('pending');
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
        Schema::dropIfExists('menu');
    }
};
