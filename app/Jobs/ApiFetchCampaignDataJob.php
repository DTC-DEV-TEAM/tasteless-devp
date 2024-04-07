<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\GCList;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\MaxAttemptsExceededException;

class ApiFetchCampaignDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $url = 'https://egc.digits.com.ph/api/egc_campaign';
            
            $secretKey = env('EGC_SECRET_KEY');
    
            $data = json_decode(file_get_contents("$url/$secretKey"));
            
            if((bool) ($data)){
                foreach ($data as $item) {
                    $item = (array) $item;
                    $item['is_fetch'] = 1;
                    $item['created_at'] = date('Y-m-d H:i:s');
                    GCList::updateOrInsert(['id' => $item['id']], $item);
                }
                
                return true;
            }else{
                return false;
            }
        }catch(MaxAttemptsExceededException $e){
            $this->release(5);
        }
    }
}
