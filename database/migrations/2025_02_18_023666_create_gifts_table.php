<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_code_id')->constrained('gift_codes')->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('friend_name');
            $table->string('friend_phone');
            $table->text('message')->nullable();
            $table->boolean('is_redeemed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
