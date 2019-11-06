<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use App\Exceptions\WorkflowException;
use Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof WorkflowException) {
            Log::info('Ошибка!', ['Текст ошибки: ' => $exception->getMessage()]);
        }
        
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
    //    if ($exception instanceof \App\Exceptions\WorkflowException)  {
    //        return $exception->render($request);
            
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found'], 404);
        } 
        else if ($exception instanceof FatalThrowableError) {
            return response()->json(['message' => $exception->getMessage()], 500);
        } 
        //else if ($exception instanceof ReflectionException) {
        //    return response()->json(['message' => $exception->getMessage()], 500);
        //}
        else if ($exception instanceof WorkflowException) {
            return response()->json(['message' => $exception->getMessage()], 422);
        } 
        else if ($exception instanceof QueryException) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
        
        return parent::render($request, $exception);
    }
}
