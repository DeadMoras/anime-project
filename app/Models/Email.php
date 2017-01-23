<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';

    public $timestamps = false;

    /**
     * @param string $type
     * @param string $view
     * @param string $to
     * @param string $subject
     * @param $data
     *
     * Метод для создания новой записи в таблице 'emails'.
     * На выбор есть: view email или обычное письмо.
     * Сообственно, type 'view' - вью, raw - обычное.
     * Для отправки сообщения будет использовать команда.
     */
    public function newEmail(string $type, string $view = '', string $to, string $subject = '', $data)
    {
        $this->type = $type;
        $this->view = $view;
        $this->to = $to;
        $this->subject = $subject;
        $this->data = serialize($data);

        $this->save();
    }
}
