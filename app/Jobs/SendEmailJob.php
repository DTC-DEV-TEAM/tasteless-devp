<?php
  
namespace App\Jobs;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\QrEmail;
use Illuminate\Support\Facades\Mail;
use App\GCList;
use Illuminate\Queue\MaxAttemptsExceededException;
use App\Http\Controllers\AdminQrCreationsController;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
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
        try{

            $path = (new AdminQrCreationsController)->manipulate_image($this->details['gc_value'], $this->details['qrCodeApiUrl'], $this->details['store_logo']);

            $this->details['qr_code_generated'] = $path;
            
            $email = new QrEmail($this->details);

            Mail::to($this->details['email'])->send($email);
            
        }catch(MaxAttemptsExceededException $e){

            DB::table('g_c_lists_devps')->where('id', $this->details['id'])->update(['store_status' => 6]);

            $this->retryUntil(now()->addSeconds(pow(2, $this->attempts())));
        }      
    }

    public function failed(){
        Log::info('Error');
        DB::table('g_c_lists_devps')->where('id', $this->details['id'])->update(['store_status' => 6]);

    }
    
}