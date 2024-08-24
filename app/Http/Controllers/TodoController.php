<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function index()
    {
        $todo = Todo::where('status', '0')->get();
        return view('todo_table', ['todo' => $todo]);
    }

    public function showAll($task)
    {
        if ($task == 0) {
            $todo = Todo::where('status', '0')->get();
        } else {
            $todo = Todo::all();
        }

        return response()->json(['status' => true, 'data' => $todo], 200);
    }

    // Create a task
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|unique:todos|max:100',
        ],['title'=>'This task is already added.']);
        if ($validated->fails()) {
            return response()->json(['status' => false, 'message' => $validated->errors()->first()], 422);
        }

        try {
            $validatedData = $validated->validated();
            $todo = Todo::create($validatedData);

            return response()->json(['status' => true, 'data' => $todo, 'message' => 'Task Added Successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Error Occurred: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return response()->json(['status' => false, 'message' => 'Failed to create task.'], 500);
        }
    }

    // Update a task
    public function update(Request $request, $id)
    {
        try {
            // Find the task by ID
            $task = Todo::findOrFail($id);

            $task->update(['status' => '1']);
            if ($request->task == 1) {
                $todo = Todo::where('status', '0')->get();
            } else {
                $todo = Todo::all();
            }

            return response()->json(['status' => true, 'data' => $todo, 'message' => 'Task updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error Occurred: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return response()->json(['status' => false, 'message' => 'An error occurred while updating the task!'], 500);
        }
    }


    // Delete a task
    public function destroy(Request $request, $id)
    {
        try {
            // Find the task by ID and delete it
            $task = Todo::findOrFail($id);
            $task->delete();
            if ($request->task == 1) {
                $todo = Todo::where('status', '0')->get();
            } else {
                $todo = Todo::all();
            }
            return response()->json(['status' => true,'data' => $todo, 'message' => 'Task deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error Occurred: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return response()->json(['status' => false, 'message' => 'Failed to delete task.'], 500);
        }
    }
}
