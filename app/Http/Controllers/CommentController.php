<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Resources\CommentCollection;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $task = Task::find((int) $request->get('task'));
       
        return new CommentCollection(Comment::getAllCommentsForTask($task));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TaskService $taskService)
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
        $createdComment = $taskService->createComment(
                                                    $currentTask, 
                                                    $request['comment'], 
                                                    $currentUser
                                                    );
        if($createdComment) {
            return response()->json(['data' => 1]);
        }
        else {
            return response()->json(['data' => 0]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
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
