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
        Schema::create('marketing_messages_settings', function (Blueprint $table) {
            $table->id();
            $table->string('message_type');
            $table->text('message_text')->nullable();
            $table->string('attachment_path')->nullable();
            $table->boolean('send_to_all')->default(false);
            $table->integer('send_after_hours')->nullable();
            $table->string('send_to_category')->nullable();
            $table->string('sending_method')->nullable();
            $table->boolean('send_on_birthday')->default(false);
            $table->boolean('send_after_purchase')->default(false);
            $table->boolean('send_after_payment')->default(false);
            $table->boolean('send_on_special_event')->default(false);
            $table->string('special_event_type')->nullable();
            $table->integer('special_event_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_messages_settings');
    }
};
