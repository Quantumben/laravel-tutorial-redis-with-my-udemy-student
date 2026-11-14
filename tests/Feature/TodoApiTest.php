<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_todo_crud_operations(): void
    {
        // Create
        $create = $this->postJson('/api/todos', [
            'title' => 'Learn Laravel',
            'description' => 'Build REST API',
        ]);

        $create->assertCreated()
            ->assertJsonPath('data.title', 'Learn Laravel');

        $id = $create->json('data.id');

        // Read all
        $this->getJson('/api/todos')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        // Read one
        $this->getJson("/api/todos/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        // Update
        $this->patchJson("/api/todos/{$id}", [
            'is_completed' => true,
        ])->assertOk();

        // Verify the updated value
        $this->getJson("/api/todos/{$id}")
            ->assertOk()
            ->assertJsonPath('data.is_completed', true);

        // Delete
        $this->deleteJson("/api/todos/{$id}")
            ->assertOk();

        // Verify deletion
        $this->getJson("/api/todos/{$id}")
            ->assertNotFound();

        $this->assertDatabaseMissing('todos', [
            'id' => $id,
        ]);
    }
}