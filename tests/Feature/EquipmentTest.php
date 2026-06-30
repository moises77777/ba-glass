<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@baglass.com',
            'password' => bcrypt('Admin123!'),
            'email_verified_at' => now(),
        ]);
    }

    public function test_equipment_list_requires_login(): void
    {
        $response = $this->get('/equipment');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_equipment_list(): void
    {
        $user = $this->createAdmin();
        $response = $this->actingAs($user)->get('/equipment');
        $response->assertStatus(200);
        $response->assertSee('Equipos');
    }

    public function test_admin_can_see_create_form(): void
    {
        $user = $this->createAdmin();
        $response = $this->actingAs($user)->get('/equipment/create');
        $response->assertStatus(200);
        $response->assertSee('Nuevo Equipo');
    }

    public function test_equipment_show_displays_details(): void
    {
        $user = $this->createAdmin();
        $category = EquipmentCategory::create([
            'code' => 'LAP',
            'name' => 'Laptops',
            'requires_serial' => true,
        ]);

        $equipment = Equipment::create([
            'internal_code' => 'EQ-0001-0002',
            'category_id' => $category->id,
            'physical_condition' => 'good',
            'operational_status' => 'operational',
            'availability_status' => 'available',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("/equipment/{$equipment->id}");
        $response->assertStatus(200);
        $response->assertSee('EQ-0001-0002');
    }
}
