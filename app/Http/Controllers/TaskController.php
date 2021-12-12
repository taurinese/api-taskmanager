<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        $tasks = Task::where('user_id', $userId)->get();
        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "body" => 'required|string'
        ]);

        $task = new Task([
            'user_id' => Auth::id(),
            'body' => $request->body,
            'completed' => false
        ]);
        $task->save();

        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$id) return response()->json(["message" => "The given data was invalid.", "errors" => ["id" => "The id is missing"]], 422);
        $task = Task::where('id', $id)->first();
        if(!$task) return response()->json(['message' => "This task doesn't exist."], 404);
        if($task->user_id != Auth::id()) return response()->json(['message' => "Unauthorized: this task doesn't belong to you."], 401);
        return response()->json(['task' => $task], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
            'completed' => 'required|boolean'
        ]);
        $task = Task::where('id', $id)->first();
        $task->body = $request->body;
        $task->completed = $request->completed;
        $task->save();
        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$id) return response()->json(["message" => "The given data was invalid.", "errors" => ["id" => "The id is missing"]]);
        try {
            Task::destroy($id);
            return response()->json([], 204);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }

    }
}
