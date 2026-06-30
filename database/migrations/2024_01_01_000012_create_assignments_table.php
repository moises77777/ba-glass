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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_number', 30)->unique();
            $table->foreignId('equipment_id')->constrained('equipment')->restrictOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            
            // Fechas
            $table->dateTime('assignment_date');
            $table->dateTime('expected_return_date')->nullable();
            $table->dateTime('actual_return_date')->nullable();
            
            // Estado de la asignación
            $table->enum('status', [
                'active', 'returned', 'transferred', 'cancelled', 'lost'
            ])->default('active');
            
            // Condiciones del equipo
            $table->enum('condition_at_assignment', [
                'excellent', 'good', 'fair', 'poor', 'damaged'
            ])->default('good');
            $table->enum('condition_at_return', [
                'excellent', 'good', 'fair', 'poor', 'damaged'
            ])->nullable();
            
            // Responsables
            $table->foreignId('assigned_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Ubicación de uso
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('work_area', 100)->nullable();
            
            // Documentación
            $table->text('assignment_notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->text('accessories_delivered')->nullable();
            $table->text('accessories_returned')->nullable();
            
            // Responsiva
            $table->string('custody_letter_path')->nullable();
            $table->string('custody_letter_folio', 30)->nullable();
            $table->dateTime('custody_letter_generated_at')->nullable();
            $table->boolean('custody_letter_signed')->default(false);
            $table->string('employee_signature')->nullable();
            $table->string('responsible_signature')->nullable();
            
            // Motivo de devolución/transferencia
            $table->enum('return_reason', [
                'employee_termination', 'equipment_upgrade', 'equipment_damage',
                'department_change', 'project_end', 'maintenance', 'other'
            ])->nullable();
            $table->text('return_reason_details')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['equipment_id', 'status']);
            $table->index(['employee_id', 'status']);
            $table->index(['assignment_date', 'status']);
            $table->index('status');
            $table->index('custody_letter_folio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
