<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('events')
                ->cascadeOnDelete();
            $table->foreignId('recurring_pattern_id')
                ->nullable()
                ->constrained('recurring_patterns')
                ->cascadeOnDelete();
            $table->string('title', 255)->index();
            $table->text('description')->nullable();
            $table->timestamp('start')->index();
            $table->timestamp('end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
