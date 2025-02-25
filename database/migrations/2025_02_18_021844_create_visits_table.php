<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the visits table.
 * Each row represents a client's visit/entry.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade')
                ->comment('References the client in the clients table');

            // Number of people in this visit
            $table->integer('number_of_people')->nullable()->comment('Number of people in the group');

            // Type of visit: direct or waiting
            $table->enum('source', ['direct', 'waiting'])->default('waiting')->comment('Direct entry or waiting list');

            // Status of the visit: waiting, called, done
            $table->enum('status', ['waiting', 'called', 'done'])->default('waiting')->comment('Current status of the visit');

            $table->softDeletes()->comment('Soft delete support');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};