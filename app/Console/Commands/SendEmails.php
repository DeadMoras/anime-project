<?php

namespace App\Console\Commands;

use App\Models\Email;
use Mail;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Something send to email';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mail = Email::firstOrFail();

        // если есть запись
        if ( $mail ) {
            // если письмо имеет свою html форму
            if ( $mail->type == 'view' ) {
                // превращаем содержимое письма в нормальную форму
                $data = unserialize($mail->data);

                // Магия
                Mail::send($mail->view, ['data' => $data], function ($message) use ($mail) {
                    $message->to($mail->to)
                            ->subject($mail->subject);
                });
            } else {
                Mail::raw($mail->data, function ($message) use ($mail) {
                    $message->to($mail->to);
                });
            }

            // после отправки удаляем письмо
            $mail->delete();
        }
    }
}
