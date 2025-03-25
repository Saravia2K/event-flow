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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'events_summary',
                'participation_stats',
                'event_status',
                'user_engagement'
            ]);
            $table->foreignId('generated_by')->constrained('users')->cascadeOnDelete();
            $table->json('parameters')->nullable(); // Filtros usados
            $table->string('file_path')->nullable(); // Ruta de archivo generado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
