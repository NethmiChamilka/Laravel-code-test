<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
         ]);

            $task = Task::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully',
                'data' => $task,
        ], 201);
    }
    public function index()
    {
        $tasks = Task::paginate(10);
         return response()->json([
            'status' => 'success',
            'data' => $tasks,
        ]);
    }
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json([
             'status' => 'success',
             'data' => $task,
            ]);
        }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,completed',
        ]);

        $task = Task::findOrFail($id);

        $task->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'data' => $task,
        ]);
    }
    public function destroy($id)
    {

        Task::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully',
        ]);
    }
}
