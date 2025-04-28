<?php

namespace Controllers;

use App\Models\Administrator;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use refreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    public function testCreate(): void
    {
        $this->assertCount(0, Comment::all());
        $response = $this->post('/api/comment/create', [
            'content' => 'Beau profil',
            'profile_id' => 1,
        ]);

        $response->assertStatus(401);
        $this->assertCount(0, Comment::all());
    }

    public function testCreateWithAuthButBadProfile(): void
    {
        $this->actingAs(Administrator::all()->first(), 'api');

        $this->assertCount(0, Comment::all());
        $response = $this->post('/api/comment/create', [
            'content' => 'amazing profil',
            'profile_id' => 10,
        ]);

        $response->assertStatus(400);
        $this->assertCount(0, Comment::all());
    }

    public function testCreateWithAuth(): void
    {
        $this->actingAs(Administrator::all()->first(), 'api');

        $this->assertCount(0, Comment::all());
        // First comment
        $response = $this->post('/api/comment/create', [
            'content' => 'amazing profil',
            'profile_id' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Comment::all());

        // Second refused
        $newResponse = $this->post('/api/comment/create', [
            'content' => 'Commentaire a refuser avec le meme administrateur',
            'profile_id' => 1,
        ]);

        $newResponse->assertStatus(400);
        $this->assertCount(1, Comment::all());

        // Third accepted on another profile
        $this->post('/api/comment/create', [
            'content' => 'Commentaire',
            'profile_id' => 2,
        ]);
        $this->assertCount(2, Comment::all());
    }
}
