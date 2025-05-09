<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
     public function index(Request $request)
    {
        $query = Task::query();

        if($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }

        if($request->filled('priority')) {
            $query->where('priority', 'like', '%' . $request->priority . '%');
        }

        if($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('due_date', [$request->from, $request->to]);
        }

        if($request->filled('project_id')) {
            $query->where('project_id', 'like', '%' . $request->project_id . '%');
        }

        return $query->get();
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());
        return response()->json([
            'message' => 'Tarea creada',
            'data' => $task,
        ], 201);
    }
    
    public function show(Task $task)
    {
        $task->load('project');
        return response()->json([
            'message' => 'Tarea encontrada',
            'data' => $task,
        ], 200);
    }
    
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response()->json([
            'message' => 'Tarea actualizada',
            'data' => $task,
        ], 200);
    }
    
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            'message' => 'Tarea eliminada',
        ], 200);
    }
}
