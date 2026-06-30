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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('internal_code', 50)->unique();
            $table->string('asset_tag', 50)->nullable()->unique();
            $table->foreignId('category_id')->constrained('equipment_categories')->restrictOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('part_number', 100)->nullable();
            
            // Especificaciones técnicas
            $table->string('processor', 150)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('storage', 100)->nullable();
            $table->string('storage_type', 50)->nullable();
            $table->string('graphics_card', 150)->nullable();
            $table->string('screen_size', 20)->nullable();
            $table->string('screen_resolution', 30)->nullable();
            $table->string('operating_system', 100)->nullable();
            $table->string('os_version', 50)->nullable();
            $table->string('os_license_key', 100)->nullable();
            $table->string('mac_address', 17)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('hostname', 100)->nullable();
            
            // Información de compra
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('purchase_order', 50)->nullable();
            $table->string('invoice_number', 50)->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->string('currency', 3)->default('MXN');
            $table->date('warranty_start_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->string('warranty_type', 100)->nullable();
            
            // Estados
            $table->enum('physical_condition', [
                'excellent', 'good', 'fair', 'poor', 'damaged', 'for_repair'
            ])->default('good');
            $table->enum('operational_status', [
                'operational', 'non_operational', 'under_repair', 'obsolete', 'pending_setup'
            ])->default('operational');
            $table->enum('availability_status', [
                'available', 'assigned', 'in_maintenance', 'retired', 'lost', 'stolen'
            ])->default('available');
            
            // Ubicación
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('specific_location', 100)->nullable();
            
            // Asignación actual
            $table->foreignId('current_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('assignment_date')->nullable();
            
            // Fechas importantes
            $table->date('delivery_date')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->date('retirement_date')->nullable();
            
            // Información adicional
            $table->text('description')->nullable();
            $table->text('observations')->nullable();
            $table->text('accessories')->nullable();
            $table->json('custom_fields')->nullable();
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['internal_code', 'availability_status']);
            $table->index(['category_id', 'availability_status']);
            $table->index(['brand_id', 'model']);
            $table->index('serial_number');
            $table->index('current_employee_id');
            $table->index('availability_status');
            $table->index('physical_condition');
            $table->index('operational_status');
            $table->index('location_id');
            $table->index(['purchase_date', 'warranty_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
