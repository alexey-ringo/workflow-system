<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use App\Services\TaskService;
use App\Services\WorkflowService;
use App\Http\Resources\Task\CommentCollection;
use App\Http\Resources\Task\CommentResource;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $task = Task::find((int) $request->input('task'));
       
        return new CommentCollection(Comment::getAllCommentsForTask($task));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, WorkflowService $workflowService)
    {
        $currentUser = $request->user('api');
        if(!$currentUser) {
            return response()->json(['data' => 0]);
        }
        
        $currentTask = Task::find($request['taskId']);
        if(!$currentTask) {
            return response()->json(['data' => 0]);
        }
        
        /*
        $validator = $request->validate([
            'slug' => 'required|string|max:255|unique:groups',
            'name' => 'required|string|max:255|unique:groups',
        ]);
        */
        $createdComment = $workflowService->createComment(
                                                    $currentTask
                                                    );
        return response()->json(['data' => [
                                        'error' => $createdComment->hasError(),
                                        'task' => $createdComment->getTask(),
                                        'message' => $createdComment->getMessage()
                                    ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //В случае использования CustomCommentResource для передачи
        //в компонент vue CommentDetails имени Process
        //$task = $comment->task;
        //$process = $task->process;
        return new CommentResource($comment);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
