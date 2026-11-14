<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TodoController extends Controller
{
    private const CACHE_TTL = 300;

    // GET /api/todos
    public function index(): JsonResponse
    {
        $todos = Cache::remember(
            'todos.all',
            self::CACHE_TTL,
            fn () => Todo::latest()->get()->toArray()
        );

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

        // The list has changed: discard the old cache.
        Cache::forget('todos.all');

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully',
            'data' => $todo,
        ], 201);
    }

    // GET /api/todos/{todo}
    public function show(int $todo): JsonResponse
    {
        $data = Cache::remember(
            "todos.item.{$todo}",
            self::CACHE_TTL,
            fn () => Todo::findOrFail($todo)->toArray()
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    // PUT/PATCH /api/todos/{todo}
    public function update(Request $request, Todo $todo): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
        ]);

        $todo->update($validated);

        // Remove both stale cache entries.
        Cache::forget('todos.all');
        Cache::forget("todos.item.{$todo->id}");

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully',
            'data' => $todo->refresh(),
        ], 200);
    }

    // DELETE /api/todos/{todo}
    public function destroy(Todo $todo): JsonResponse
    {
        $todoId = $todo->id;

        $todo->delete();

        // Remove cached list and deleted item.
        Cache::forget('todos.all');
        Cache::forget("todos.item.{$todoId}");

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully',
        ], 200);
    }
}