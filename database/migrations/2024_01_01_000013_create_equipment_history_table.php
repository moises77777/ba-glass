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
        Schema::create('equipment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->foreignId('assignment_id')->nullable()->constrained('assignments')->nullOnDelete();
            
            // Tipo de movimiento
            $table->enum('movement_type', [
                'assignment',           // Asignación a empleado
                'return',               // Devolución
                'transfer',             // Transferencia entre empleados
                'location_change',      // Cambio de ubicación
                'status_change',        // Cambio de estado
                'maintenance_start',    // Inicio de mantenimiento
                'maintenance_end',      // Fin de mantenimiento
                'condition_update',     // Actualización de condición
                'retirement',           // Baja del equipo
                'reactivation',         // Reactivación
                'data_update',          // Actualización de datos
                'image_added',          // Imagen agregada
                'document_added',       // Documento agregado
                'other'                 // Otro
            ]);
            
            // Empleados involucrados
            $table->foreignId('previous_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('new_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            
            // Ubicaciones
            $table->foreignId('previous_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('new_location_id')->nullable()->constrained('locations')->nullOnDelete();
            
            // Estados anteriores y nuevos
            $table->string('previous_status', 50)->nullable();
            $table->string('new_status', 50)->nullable();
            $table->string('previous_condition', 50)->nullable();
            $table->string('new_condition', 50)->nullable();
            
            // Detalles del movimiento
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->json('changes')->nullable();
            $table->json('metadata')->nullable();
            
            // Auditoría
            $table->foreignId('performed_by')->constrained('users')->restrictOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            
            $table->timestamp('performed_at');
            $table->timestamps();

            // Índices
            $table->index(['equipment_id', 'movement_type']);
            $table->index(['equipment_id', 'performed_at']);
            $table->index('movement_type');
            $table->index('performed_at');
            $table->index('previous_employee_id');
            $table->index('new_employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_history');
    }
};
