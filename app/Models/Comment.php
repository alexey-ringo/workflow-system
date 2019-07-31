<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Task;

class Comment extends Model
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
