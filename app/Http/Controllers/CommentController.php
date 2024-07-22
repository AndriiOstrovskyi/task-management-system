<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class CommentController extends Controller
{
    use HttpResponses;

    public function store(StoreCommentRequest $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        $comment = $task->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id()
        ]);

        $comment->load('task', 'user');

        return new CommentResource($comment);
    }

    public function index($taskId)
    {
        $task = Task::findOrFail($taskId);
        
        $comments = $task->comments()->get();

        $comments->load('task', 'user');

        return CommentResource::collection($comments);
    }

    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if($comment->user_id !== Auth::user()->id) {
            return $this->error('You are not authorized to delete this comment', 403);
        }

        $comment->delete();

        return $this->success(null, 'Comment deleted successfully');
    }
}
