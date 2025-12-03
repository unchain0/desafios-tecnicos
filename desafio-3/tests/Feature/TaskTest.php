<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_tasks(): void
    {
        Task::create(['title' => 'Test Task']);

        $response = $this->get('/tasks');

        $response->assertStatus(200);
        $response->assertSee('Test Task');
    }

    public function test_index_shows_empty_state(): void
    {
        $response = $this->get('/tasks');

        $response->assertStatus(200);
        $response->assertSee('Nenhuma task cadastrada');
    }

    public function test_store_creates_task(): void
    {
        $response = $this->post('/tasks', ['title' => 'Nova Task']);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['title' => 'Nova Task', 'done' => false]);
    }

    public function test_store_validates_title_required(): void
    {
        $response = $this->post('/tasks', ['title' => '']);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_store_validates_title_max_length(): void
    {
        $response = $this->post('/tasks', ['title' => str_repeat('a', 256)]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_toggle_marks_task_as_done(): void
    {
        $task = Task::create(['title' => 'Test Task', 'done' => false]);

        $response = $this->patch("/tasks/{$task->id}/toggle");

        $response->assertRedirect('/tasks');
        $this->assertTrue($task->fresh()->done);
    }

    public function test_toggle_marks_task_as_pending(): void
    {
        $task = Task::create(['title' => 'Test Task', 'done' => true]);

        $response = $this->patch("/tasks/{$task->id}/toggle");

        $response->assertRedirect('/tasks');
        $this->assertFalse($task->fresh()->done);
    }

    public function test_destroy_deletes_task(): void
    {
        $task = Task::create(['title' => 'Test Task']);

        $response = $this->delete("/tasks/{$task->id}");

        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_root_redirects_to_tasks(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/tasks');
    }

    public function test_store_sanitizes_html_tags(): void
    {
        $response = $this->post('/tasks', ['title' => '<script>alert("xss")</script>Task']);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['title' => 'alert("xss")Task']);
        $this->assertDatabaseMissing('tasks', ['title' => '<script>alert("xss")</script>Task']);
    }

    public function test_toggle_returns_404_for_nonexistent_task(): void
    {
        $response = $this->patch('/tasks/999/toggle');

        $response->assertStatus(404);
    }

    public function test_destroy_returns_404_for_nonexistent_task(): void
    {
        $response = $this->delete('/tasks/999');

        $response->assertStatus(404);
    }

    public function test_index_returns_json_when_requested(): void
    {
        Task::create(['title' => 'JSON Task']);

        $response = $this->getJson('/tasks');

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'JSON Task']);
    }

    public function test_store_returns_json_when_requested(): void
    {
        $response = $this->postJson('/tasks', ['title' => 'Nova Task JSON']);

        $response->assertStatus(201);
        $response->assertJsonFragment(['title' => 'Nova Task JSON']);
        $this->assertDatabaseHas('tasks', ['title' => 'Nova Task JSON', 'done' => false]);
    }

    public function test_store_validates_title_max_length_json(): void
    {
        $response = $this->postJson('/tasks', ['title' => str_repeat('a', 256)]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_toggle_returns_json_when_requested(): void
    {
        $task = Task::create(['title' => 'JSON Toggle', 'done' => false]);

        $response = $this->patchJson("/tasks/{$task->id}/toggle");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $task->id, 'done' => true]);
    }

    public function test_destroy_returns_json_when_requested(): void
    {
        $task = Task::create(['title' => 'JSON Delete']);

        $response = $this->deleteJson("/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
