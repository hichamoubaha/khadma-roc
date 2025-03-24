<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Annonce;

class AnnonceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_recruteur_peut_creer_une_annonce()
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);

        $response = $this->actingAs($recruteur, 'sanctum')->postJson('/api/annonces', [
            'titre' => 'Développeur Laravel',
            'description' => 'Nous recherchons un expert Laravel.',
            'salaire' => 50000
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('annonces', ['titre' => 'Développeur Laravel']);
    }

    /** @test */
    public function un_candidat_ne_peut_pas_creer_une_annonce()
    {
        $candidat = User::factory()->create(['role' => 'candidat']);

        $response = $this->actingAs($candidat, 'sanctum')->postJson('/api/annonces', [
            'titre' => 'Développeur Laravel',
            'description' => 'Nous recherchons un expert Laravel.',
            'salaire' => 50000
        ]);

        $response->assertStatus(403);
    }
}
