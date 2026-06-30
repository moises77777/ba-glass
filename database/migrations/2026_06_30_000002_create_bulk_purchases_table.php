<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->foreignId('equipment_model_id')->nullable()->constrained('equipment_models')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('equipment_categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('model_name')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->string('currency', 3)->default('MXN');
            $table->string('purchase_order')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_start_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->string('warranty_type')->nullable();
            $table->string('location_id')->nullable();
            $table->string('physical_condition')->default('good');
            $table->string('operational_status')->default('pending_setup');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_purchases');
    }
};
