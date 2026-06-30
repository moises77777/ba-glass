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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name', 150)->nullable();
            $table->string('user_email', 150)->nullable();
            
            // Acción realizada
            $table->enum('action', [
                'create', 'update', 'delete', 'restore', 'login', 'logout',
                'export', 'import', 'print', 'download', 'view', 'search',
                'assign', 'unassign', 'transfer', 'approve', 'reject', 'other'
            ]);
            
            // Modelo afectado
            $table->string('auditable_type', 100);
            $table->unsignedBigInteger('auditable_id')->nullable();
            
            // Datos
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('description')->nullable();
            
            // Información de la solicitud
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable();
            
            // Información adicional
            $table->json('metadata')->nullable();
            $table->string('tags', 255)->nullable();
            
            $table->timestamp('created_at')->useCurrent();

            // Índices
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_id', 'action']);
            $table->index('action');
            $table->index('created_at');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
