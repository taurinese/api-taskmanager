<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{

    public function test_create_task()
    {
        Sanctum::actingAs(User::find(1));
        $data = [
            'body' => "Task number one",
        ];
        $response = $this->postJson('/api/tasks', $data);
        $this->task = $response;
        $response->assertStatus(200);
    }

    public function test_create_task_without_body()
    {
        Sanctum::actingAs(User::find(1));
        $response = $this->postJson('/api/tasks', []);
        $this->task = $response;
        $response->assertStatus(422);
    }

    public function test_get_all_tasks()
    {
        Sanctum::actingAs(User::find(1));
        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
    }

    public function test_get_task()
    {
        Sanctum::actingAs(User::find(1));
        // dd(Task::where('user_id', 1)->first()->id);
        $response = $this->get('/api/tasks/' . Task::where('user_id', 1)->first()->id);
        $response->assertStatus(200);
    }

    public function test_get_task_with_wrong_userid()
    {
        Sanctum::actingAs(User::find(2));
        // dd(Task::where('user_id', 1)->first()->id);
        $response = $this->get('/api/tasks/' . Task::where('user_id', 1)->first()->id);
        $response->assertStatus(401);
    }

    public function test_update_task()
    {
        Sanctum::actingAs(User::find(1));
        $data = [
            'body' => "Updated task",
            'completed' => true
        ];
        $response = $this->putJson('/api/tasks/' . Task::where('user_id', 1)->first()->id, $data);
        $response->assertStatus(200);
    }
    public function test_delete_task()
    {
        Sanctum::actingAs(User::find(1));
        $response = $this->delete('/api/tasks/' . Task::where('user_id', 1)->first()->id);
        $response->assertStatus(204);
    }

}
