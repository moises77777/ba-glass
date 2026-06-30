<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->boolean('has_charger')->default(false)->after('accessories');
            $table->string('charger_details', 150)->nullable()->after('has_charger');
            $table->boolean('has_mouse')->default(false)->after('charger_details');
            $table->string('mouse_details', 150)->nullable()->after('has_mouse');
            $table->boolean('has_keyboard')->default(false)->after('mouse_details');
            $table->boolean('has_power_strip')->default(false)->after('has_keyboard');
            $table->boolean('has_bag_case')->default(false)->after('has_power_strip');
            $table->string('adapters', 255)->nullable()->after('has_bag_case');
            $table->string('other_accessories', 255)->nullable()->after('adapters');
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn([
                'has_charger', 'charger_details',
                'has_mouse', 'mouse_details',
                'has_keyboard', 'has_power_strip', 'has_bag_case',
                'adapters', 'other_accessories',
            ]);
        });
    }
};
