<?php

namespace App\Services;

use App\Models\Comment;


class CommentResponse
{
    private $comment;
    private $message;
    
    public function __construct(string $message, Comment $comment)
    {
        $this->comment = $comment;
        $this->message = $message;
        
    }
    
    
    public function getComment(): Comment
    {
        return $this->comment;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}