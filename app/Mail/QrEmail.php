<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Exception;
use Illuminate\Support\Facades\Log;
use DB;

class QrEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {  

        return $this->view('redeem_qr.sendemail', $this->data)
            ->subject($this->data['email_subject'])
            ->from(env('MAIL_USERNAME'), env('APP_NAME'));
    }

    public function failed()
    {
        Log::info('Error From the System');
        DB::table('g_c_lists_devps')->where('id', $this->data['id'])->update(['store_status' => 6]);
    }
}
