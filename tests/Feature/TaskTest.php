<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task()
    {

        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api')->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
        ]);

        $response->assertStatus(201)
        ->assertJson([
            'status' => 'success',
            'message' => 'Task created successfully',
        ]);
    }

    public function test_get_all_tasks()
    {
        $user = User::factory()->create();
        Task::factory()->count(10)->create();
        $response = $this->actingAs($user, 'api')->getJson('/api/tasks');
        $response->assertStatus(200)
        ->assertJsonStructure([
            'status', 'data' => [ 'data' => [ '*' => ['id', 'title', 'description', 'status', 'created_at', 'updated_at'] ] ]
        ]);
    }

    public function test_get_single_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $response = $this->actingAs($user, 'api')->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(200) ->assertJson([
            'status' => 'success',
            'data' => [ 'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status, ]
        ]);
    }

    public function test_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $response = $this->actingAs($user, 'api')
        ->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'This is an updated task',
            'status' => 'completed',
        ]);

        $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'message' => 'Task updated successfully',
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'description' => 'This is an updated task',
            'status' => 'completed',
        ]);
    }

    public function test_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $response = $this->actingAs($user, 'api')->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(200) ->assertJson([
            'status' => 'success',
            'message' => 'Task deleted successfully',
        ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}