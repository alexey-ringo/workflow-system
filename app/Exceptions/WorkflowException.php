<?php

namespace App\Exceptions;

use Exception;
use ErrorException;
use Log;

class WorkflowException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
    //    Log::info('Ошибка!', ['Текст ошибки: ' => $exception->getMessage()]);
    }
    //public function report(Exception $exception)
    //{
    //    Log::info('Ошибка!', ['Текст ошибки: ' => $exception->getMessage()]);
    //}
    
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
    public function render()
    {
    
    }

//    public function render($request)
//    {
    //    return response()->json([
    //        'error' => $this->getMessage()
    //    ]);
//    }
    
    //public function render($request, Exception $exception)
    //{
    //    return response()->json(['error' => $exception->getMessage()], 401);
        //return parent::render($request, $exception);
    //}
}
