<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService;
    }

    public function test_get_all_returns_tasks(): void
    {
        Task::create(['title' => 'First']);
        Task::create(['title' => 'Second']);

        $tasks = $this->service->getAll();

        $this->assertCount(2, $tasks);
    }

    public function test_create_creates_task(): void
    {
        $task = $this->service->create(['title' => 'New Task']);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('New Task', $task->title);
        $this->assertDatabaseHas('tasks', ['title' => 'New Task', 'done' => false]);
    }

    public function test_toggle_changes_done_status(): void
    {
        $task = Task::create(['title' => 'Test', 'done' => false]);

        $updated = $this->service->toggle($task);

        $this->assertTrue($updated->done);

        $updated = $this->service->toggle($task->fresh());

        $this->assertFalse($updated->done);
    }

    public function test_delete_removes_task(): void
    {
        $task = Task::create(['title' => 'To Delete']);

        $this->service->delete($task);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
