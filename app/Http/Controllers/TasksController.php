<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskReguest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TasksResource;
use App\Traits\HttpResponses;

class TasksController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $tasks = Task::with(['user', 'team'])
            ->where('user_id', $userId)
            ->orWhereHas('team.users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->get();

        return TasksResource::collection($tasks);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
            'team_id' => $request->team_id
        ]);

        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show($taskId)
    {
        $task = Task::with(['user', 'team'])->findOrFail($taskId);

        $isOwner = $task->user_id === Auth::user()->id;
        $isTeamMember = $task->team && $task->team->users->contains(Auth::id());

        if (!$isOwner && !$isTeamMember) {
            return $this->error('You are not authorized to view this task', 403);
        }

        return new TasksResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskReguest $request, Task $task)
    {
        if($task->user_id !== Auth::user()->id) {
            return $this->error('You are not authorized to update this task', 403);
        }

        $task->update($request->all());
        
        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if($task->user_id !== Auth::user()->id) {
            return $this->error('You are not authorized to delete this task', 403);
        }

        $task->delete();

        return $this->success(null, 'Task deleted successfully');
    }
}
