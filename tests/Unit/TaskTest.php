<?php

namespace Tests\Unit;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{

    protected $task;

    public function test_create_task()
    {
        $data = [
            'body' => "Task number one"
        ];
        $response = $this->postJson('/api/tasks', $data);
        $this->task = $response;
        $response->assertStatus(200);
    }
    public function test_get_all_tasks()
    {
        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
    }
    public function test_get_task()
    {
        $response = $this->get('/api/tasks/' . $this->task->id);
        $response->assertStatus(200);
    }
    public function test_update_task()
    {
        $data= $this->task;
        $data->body = 'Update task';
        $response = $this->putJson('/api/tasks/' . $this->task->id, $data);
        $response->assertStatus(200);
    }
    public function test_delete_task()
    {
        $response = $this->delete('/api/tasks/' . $this->task->id);
        $response->assertStatus(204);
    }

}
