<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportNewArticlesFailNotification;

class InvalidArticleDataException extends Exception
{
    public $data;
    public $invalidData;

    public function __construct(array $data, array $invalidData)
    {
        $this->data = $data;
        $this->invalidData = $invalidData;
    }

    public function report()
    {
        Notification::route('mail', env('APP_ADMIN_MAIL'))
            ->notify(new ImportNewArticlesFailNotification(
                'Dados inválidos na importação - '
                    . 'Dados importados: ' . json_encode($this->data) . ' - '
                    . 'Dados inválidos: ' . json_encode($this->invalidData) . ''
            ));
    }
}
