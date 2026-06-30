<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('equipment_categories')->nullOnDelete();
            $table->string('name');
            $table->string('part_number')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('storage_type')->nullable();
            $table->string('graphics_card')->nullable();
            $table->string('screen_size')->nullable();
            $table->string('operating_system')->nullable();
            $table->decimal('reference_price', 12, 2)->default(0);
            $table->string('currency', 3)->default('MXN');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->foreignId('equipment_model_id')->nullable()->after('brand_id')->constrained('equipment_models')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropForeign(['equipment_model_id']);
            $table->dropColumn('equipment_model_id');
        });
        Schema::dropIfExists('equipment_models');
    }
};
