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
        Schema::create('marketing_messages', function (Blueprint $table) {
            $table->id();
            $table->text('message_body');
            $table->string('attachment_path')->nullable();
            $table->enum('send_to', ['all', 'custom'])->default('all');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->dateTime('schedule_time')->nullable();
            $table->boolean('sent_now')->default(false);
            $table->enum('delivery_method', ['whatsapp', 'sms', 'email']);
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_messages');
    }
};
