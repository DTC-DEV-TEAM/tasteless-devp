<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use DB;

class SendEmailOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->details;
        
        try{
            Mail::send(['html' => 'email_testing.otp-email'], $data, function($message) use ($data) {
                $message->to($data['email'])->subject($data['subject_of_the_email']);
                $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
            });

            DB::table('g_c_lists_devps_customers')
                ->update([
                    'email_is_sent' => 1
                ]);

            return true;
        }catch(\Exception $e){
            DB::table('g_c_lists_devps_customers')
                ->update([
                    'email_is_sent' => 0
                ]);
            return false;
        }



    }
}
