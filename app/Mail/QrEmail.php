<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\GCList;

class QrEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // GCList::find($this->data['id'])->update(['email_is_sent'=>1]);     

        return $this->view('redeem_qr.sendemail', $this->data)
            ->subject($this->data['email_subject'])
            ->from(env('MAIL_USERNAME'), env('APP_NAME'));
    }

    
}
