<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marketing_calendar', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['public', 'local', 'private']);
            $table->date('event_date');
            $table->enum('offer_type', ['discount', 'free_offer'])->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->text('free_offer_details')->nullable();
            $table->integer('message_send_before_days')->nullable();
            $table->string('customer_category')->nullable();
            $table->text('message_content')->nullable();
            $table->boolean('is_expired')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_calendar');
    }
};
