<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // GET /api/todos
    public function index(): JsonResponse
    {
        $todos = Todo::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $todos,
        ], 200);
    }

    // POST /api/todos
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
        ]);

        $todo = Todo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Todo created',
            'data' => $todo,
        ], 201);
    }

    // GET /api/todos/{todo}
    public function show(Todo $todo): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $todo,
        ], 200);
    }

    // PUT/PATCH /api/todos/{todo}
    public function update(Request $request,Todo $todo): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
        ]);

        $todo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Todo updated',
            'data' => $todo->refresh(),
        ], 200);
    }

    // DELETE /api/todos/{todo}
    public function destroy(Todo $todo): JsonResponse
    {
        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted',
        ], 200);
    }
}