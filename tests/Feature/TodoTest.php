<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_todo()
    {
        $data = [
            'title' => 'Test Todo',
            'status' => 'Pending'
        ];

        $response = $this->postJson('/api/todos', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id', 'title', 'status', 'created_at', 'updated_at'
                 ]);

        $this->assertDatabaseHas('todos', $data);
    }

    /** @test */
    public function it_can_fetch_all_todos()
    {
        Todo::factory()->count(5)->create();

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
                 ->assertJsonCount(5)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'status', 'created_at', 'updated_at']
                 ]);
    }

    /** @test */
    public function it_can_fetch_a_single_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->getJson("/api/todos/{$todo->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id', 'title', 'status', 'created_at', 'updated_at'
                 ])
                 ->assertJson([
                     'id' => $todo->id,
                     'title' => $todo->title,
                     'status' => $todo->status,
                 ]);
    }

    /** @test */
    public function it_can_update_a_todo()
    {
        $todo = Todo::factory()->create();

        $data = [
            'title' => 'Updated Todo',
            'status' => 'in_progress'
        ];

        $response = $this->putJson("/api/todos/{$todo->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id', 'title', 'status', 'created_at', 'updated_at'
                 ])
                 ->assertJson($data);

        $this->assertDatabaseHas('todos', $data);
    }

    /** @test */
    public function it_can_delete_a_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}

