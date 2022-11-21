<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportNewArticlesFailNotification;

class NotFoundNewArticlesApiResponseException extends Exception
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        Notification::route('mail', env('APP_ADMIN_MAIL'))
                    ->notify(new ImportNewArticlesFailNotification('Erro do servidor - ' . $this->getMessage()));
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return $this->message;
    }
}
