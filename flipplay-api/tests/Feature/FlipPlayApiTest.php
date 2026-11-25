<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlipPlayApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_list_search_and_filter_media(): void
    {
        $this->getJson('/api/media')
            ->assertOk()
            ->assertJsonCount(5, 'data');

        $this->getJson('/api/media?title=Frieren')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.code', 'ANM-002');

        $this->getJson('/api/media?search=Chainsaw')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.code', 'MNG-002');

        $this->getJson('/api/media?type=anime')
            ->assertOk()
            ->assertJsonCount(3, 'data');

        $this->getJson('/api/media?type=manga')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_can_show_media_detail_with_collector_users_and_pivot_progress(): void
    {
        $frieren = Media::where('code', 'ANM-002')->firstOrFail();

        $this->getJson("/api/media/{$frieren->id}")
            ->assertOk()
            ->assertJsonPath('data.code', 'ANM-002')
            ->assertJsonCount(1, 'data.users')
            ->assertJsonPath('data.users.0.email', 'farrelmaulana.anshari@gmail.com')
            ->assertJsonPath('data.users.0.progress', 14);
    }

    public function test_can_create_update_and_delete_media(): void
    {
        $createResponse = $this->postJson('/api/media', [
            'code' => 'ANM-004',
            'title' => 'Jujutsu Kaisen',
            'type' => 'anime',
            'genre' => 'Aksi, Supranatural',
            'rating' => 8.75,
            'synopsis' => 'Yuji Itadori memakan jari Sukuna.',
            'is_available' => true,
            'total_episodes' => 24,
            'studio' => 'MAPPA',
            'resolution' => '1080p Full HD',
            'has_sub_indo' => true,
        ]);

        $createResponse->assertCreated()
            ->assertJsonPath('data.code', 'ANM-004')
            ->assertJsonPath('data.studio', 'MAPPA');

        $mediaId = $createResponse->json('data.id');

        $this->patchJson("/api/media/{$mediaId}", [
            'rating' => 9.10,
        ])
            ->assertOk()
            ->assertJsonPath('data.rating', 9.1);

        $this->putJson("/api/media/{$mediaId}", [
            'code' => 'ANM-004',
            'title' => 'Jujutsu Kaisen: Season 2',
            'type' => 'anime',
            'genre' => 'Aksi, Dark Fantasy',
            'rating' => 9.25,
            'synopsis' => 'Shibuya Incident.',
            'total_episodes' => 23,
            'studio' => 'MAPPA',
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Jujutsu Kaisen: Season 2');

        $this->deleteJson("/api/media/{$mediaId}")
            ->assertOk();

        $this->getJson("/api/media/{$mediaId}")
            ->assertNotFound();
    }

    public function test_can_manage_user_collection_happy_path(): void
    {
        $user = User::where('email', 'farrelmaulana.anshari@gmail.com')->firstOrFail();
        $soloLeveling = Media::where('code', 'ANM-003')->firstOrFail();

        $this->getJson("/api/users/{$user->id}/collection")
            ->assertOk()
            ->assertJsonPath('data.tier', 'VIP Member FlipPlay')
            ->assertJsonCount(3, 'data.collection');

        $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => $soloLeveling->id,
            'progress' => 5,
        ])
            ->assertCreated()
            ->assertJsonCount(4, 'data.collection');

        $this->patchJson("/api/users/{$user->id}/collection/{$soloLeveling->id}", [
            'progress' => 12,
        ])
            ->assertOk()
            ->assertJsonPath('data.progress', 12);

        $this->deleteJson("/api/users/{$user->id}/collection/{$soloLeveling->id}")
            ->assertOk();

        $this->getJson("/api/users/{$user->id}/collection")
            ->assertOk()
            ->assertJsonCount(3, 'data.collection');
    }

    public function test_error_paths_return_proper_json_statuses(): void
    {
        $user = User::where('email', 'farrelmaulana.anshari@gmail.com')->firstOrFail();
        $frieren = Media::where('code', 'ANM-002')->firstOrFail();
        $chainsawMan = Media::where('code', 'MNG-002')->firstOrFail();

        // Empty title -> 422
        $this->postJson('/api/media', [
            'code' => 'ANM-999',
            'title' => '',
            'type' => 'anime',
            'genre' => 'Aksi',
            'rating' => 8.0,
            'synopsis' => 'Test',
            'total_episodes' => 12,
            'studio' => 'MAPPA',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        // Duplicate title -> 422
        $this->postJson('/api/media', [
            'code' => 'ANM-998',
            'title' => 'Solo Leveling: Arise',
            'type' => 'anime',
            'genre' => 'Aksi',
            'rating' => 8.0,
            'synopsis' => 'Test',
            'total_episodes' => 12,
            'studio' => 'A-1 Pictures',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        // Duplicate media in user collection -> 409
        $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => $frieren->id,
            'progress' => 15,
        ])->assertStatus(409);

        // Delete non-attached media from collection -> 404
        $this->deleteJson("/api/users/{$user->id}/collection/{$chainsawMan->id}")
            ->assertNotFound();

        // Non-existent media ID -> 404
        $this->getJson('/api/media/99999')
            ->assertNotFound();

        // Non-existent user ID -> 404
        $this->getJson('/api/users/99999/collection')
            ->assertNotFound();
    }
}
