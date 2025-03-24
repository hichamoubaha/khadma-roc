<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class StatistiquesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_admin_peut_acceder_aux_statistiques()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/statistiques/admin');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Admin Statistics Data']);
    }

    /** @test */
    public function un_recruteur_ne_peut_pas_acceder_aux_statistiques_admin()
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);

        $response = $this->actingAs($recruteur, 'sanctum')->getJson('/api/statistiques/admin');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Accès non autorisé']);
    }
}
