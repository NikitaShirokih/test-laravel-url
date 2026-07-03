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
        Schema::create('link_visits', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('short_link_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->ipAddress('ip_address');

            $table->timestamps();

            $table->index('short_link_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_visits');
    }
};
