<?php

namespace Tests\Feature;

use App\Livewire\TaskManager;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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

    public function test_root_redirects_to_tasks(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/tasks');
    }

    public function test_can_create_task(): void
    {
        Livewire::test(TaskManager::class)
            ->set('title', 'Nova Task')
            ->call('addTask')
            ->assertSet('title', '')
            ->assertSet('message', 'Task criada com sucesso!');

        $this->assertDatabaseHas('tasks', ['title' => 'Nova Task', 'done' => false]);
    }

    public function test_create_task_validates_title_required(): void
    {
        Livewire::test(TaskManager::class)
            ->set('title', '')
            ->call('addTask')
            ->assertHasErrors(['title' => 'required']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_create_task_validates_title_max_length(): void
    {
        Livewire::test(TaskManager::class)
            ->set('title', str_repeat('a', 256))
            ->call('addTask')
            ->assertHasErrors(['title' => 'max']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_can_toggle_task_to_done(): void
    {
        $task = Task::create(['title' => 'Test Task', 'done' => false]);

        Livewire::test(TaskManager::class)
            ->call('toggleTask', $task);

        $this->assertTrue($task->fresh()->done);
    }

    public function test_can_toggle_task_to_pending(): void
    {
        $task = Task::create(['title' => 'Test Task', 'done' => true]);

        Livewire::test(TaskManager::class)
            ->call('toggleTask', $task);

        $this->assertFalse($task->fresh()->done);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::create(['title' => 'Test Task']);

        Livewire::test(TaskManager::class)
            ->call('deleteTask', $task)
            ->assertSet('message', 'Task excluída com sucesso!');

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_displays_task_count(): void
    {
        Task::create(['title' => 'Task 1']);
        Task::create(['title' => 'Task 2']);

        $response = $this->get('/tasks');

        $response->assertSee('2 itens');
    }

    public function test_displays_completed_task_differently(): void
    {
        Task::create(['title' => 'Completed Task', 'done' => true]);

        $response = $this->get('/tasks');

        $response->assertSee('✅');
        $response->assertSee('Completed Task');
    }
}
