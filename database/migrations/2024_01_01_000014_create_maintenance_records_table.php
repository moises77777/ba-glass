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
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 30)->unique();
            $table->foreignId('equipment_id')->constrained('equipment')->restrictOnDelete();
            
            // Tipo de mantenimiento
            $table->enum('type', [
                'preventive', 'corrective', 'upgrade', 'cleaning', 'inspection', 'other'
            ])->default('corrective');
            
            // Estado del mantenimiento
            $table->enum('status', [
                'pending', 'in_progress', 'completed', 'cancelled', 'on_hold'
            ])->default('pending');
            
            // Prioridad
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Fechas
            $table->dateTime('reported_at');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('scheduled_date')->nullable();
            
            // Descripción del problema y solución
            $table->string('title', 150);
            $table->text('problem_description');
            $table->text('diagnosis')->nullable();
            $table->text('solution')->nullable();
            $table->text('parts_replaced')->nullable();
            
            // Costos
            $table->decimal('labor_cost', 10, 2)->nullable();
            $table->decimal('parts_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            
            // Proveedor/Técnico
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('technician_name', 150)->nullable();
            $table->string('technician_phone', 20)->nullable();
            
            // Responsables
            $table->foreignId('reported_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Condición antes y después
            $table->enum('condition_before', [
                'excellent', 'good', 'fair', 'poor', 'damaged', 'non_operational'
            ])->nullable();
            $table->enum('condition_after', [
                'excellent', 'good', 'fair', 'poor', 'damaged', 'non_operational'
            ])->nullable();
            
            // Documentación
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['equipment_id', 'status']);
            $table->index(['status', 'priority']);
            $table->index('type');
            $table->index('reported_at');
            $table->index('scheduled_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
