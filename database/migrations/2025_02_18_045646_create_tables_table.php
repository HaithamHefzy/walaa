<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the tables table.
 * Stores info about each table (room number, capacity, status, etc.).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->integer('room_number')->comment('Room number, unique identifier');
            $table->integer('table_capacity')->comment('Capacity for the Table');
            $table->integer('table_number')->comment('Table number within the room');
            $table->enum('status', ['available', 'unavailable'])
                ->default('available')
                ->comment('Whether the table is available or not');
            $table->softDeletes()->comment('Soft delete support');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
