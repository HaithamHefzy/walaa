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
        Schema::create('gift_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->enum('discount_type',['percentage','fixed']);
            $table->decimal('discount_value', 8, 2);
            $table->integer('validity_days')->nullable();
            $table->integer('validity_after_hours')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_codes');
    }
};