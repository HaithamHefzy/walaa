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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->foreignId('discount_code_id')->constrained('discount_codes')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->time('created_time');
            $table->enum('usage_status', ['used', 'not_used'])->default('not_used');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
