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
    public function report(Exception $exception)
    {
        Log::info('Ошибка!', ['Текст ошибки: ' => $exception->getMessage()]);
    }
}
