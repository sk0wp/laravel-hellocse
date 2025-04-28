<?php

namespace Controllers;

use App\Models\Administrator;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use refreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    public function testList(): void
    {
        $response = $this->get('/api/profiles');

        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'firstname',
                    'lastname',
                    'image',
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    public function testListWithAuth(): void
    {
        $this->actingAs(Administrator::all()->first(), 'api');

        $response = $this->get('/api/profiles');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'firstname',
                    'lastname',
                    'image',
                    'status'
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    public function testCreate(): void
    {
        $this->assertCount(5, Profile::all());
        $response = $this->post('/api/profile/create', [
            'firstname' => 'Jeanl',
            'lastname' => 'Pierre',
            'status' => 'waiting',
            'image' => UploadedFile::fake()->create('avatar.jpg', 500, 'image/jpeg'),
        ]);

        $response->assertStatus(401);
        $this->assertCount(5, Profile::all());
    }

    public function testCreateWithAuth(): void
    {
        $this->actingAs(Administrator::all()->first(), 'api');

        $this->assertCount(5, Profile::all());
        $response = $this->post('/api/profile/create', [
            'firstname' => 'Jean Michel',
            'lastname' => 'Aulas',
            'status' => 'waiting',
            'image' => UploadedFile::fake()->create('avatar.jpg', 500, 'image/jpeg'),
        ]);

        $response->assertStatus(201);
        $this->assertCount(6, Profile::all());
    }

    public function testEdit()
    {
        $response = $this->post('/api/profile/edit', ['id' => 1, 'lastname' => 'Aulas']);

        $response->assertStatus(401);
    }

    public function testEditWithAuth()
    {
        $this->actingAs(Administrator::all()->first(), 'api');

        $profileBeforeEdit = Profile::query()->where('id', '=', 2)->first();

        $response = $this->post('/api/profile/edit', ['id' => 2, 'lastname' => 'Aulas 69']);

        $response->assertStatus(200);

        $profileAfterEdit = Profile::query()->where('id', '=', 2)->first();
        $this->assertInstanceOf(Profile::class, $profileAfterEdit);
        $this->assertNotEquals($profileBeforeEdit, $profileAfterEdit);
        $this->assertEquals('Aulas 69', $profileAfterEdit->lastname);
    }

    public function testDelete()
    {
        $response = $this->delete('/api/profile/edit', ['id' => 1]);

        $response->assertStatus(401);
    }

    public function testDeleteWithAuth()
    {
        $this->actingAs(Administrator::all()->first(), 'api');

        $this->assertCount(5, Profile::all());
        $response = $this->delete('/api/profile/edit', ['id' => 1]);

        $this->assertCount(4, Profile::all());
        $response->assertStatus(200);
    }
}
