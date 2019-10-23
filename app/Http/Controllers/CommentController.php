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
        $currentTask = Task::find($request['taskId']);
        if(!$currentTask) {
            return response()->json(['message' => 'Задача, к которой собираются добавить комментарий - не найдена!'], 422);
        }       
       
        $createdComment = $workflowService->createComment($currentTask);
        
        return response()->json(['message' => $createdComment->getMessage()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::find($id);
        //В случае использования CustomCommentResource для передачи
        //в компонент vue CommentDetails имени Process
        //$task = $comment->task;
        //$process = $task->process;
        if($comment) {
            return new CommentResource($comment);
        }
        else {
            return response()->json(['message' => 'Комментарий с идентификатором id ' . $id . ' не найден!'], 422);
        }
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
