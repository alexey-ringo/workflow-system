<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Collection;

class Comment extends AbstractEntity
{
    protected $table = 'comments';
    
    protected $fillable = [
        'task_num', 'task_seq_num', 'task_id', 'comment_seq', 'common_comment_seq', 
        'comment', 'user_id', 'user_name', 'user_email'
    ];
    
    public function task() {
        return $this->belongsTo(Task::class);
  }
    
    public static function getAllCommentsForTask(Task $task): Collection 
    {
        $allComments = self::with('task')
                        ->whereHas('task', function($q) use($task) {
                                $q->where('tasks.task', $task->task);
                        })->get();
        
        return $allComments;
    }
}
