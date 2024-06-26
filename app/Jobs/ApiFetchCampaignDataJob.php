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
   
            $prod = config('jobs-url.api.tevp_campaign_URL');

            $url = "$prod/egc_campaign";
            
            $secretKey = config('jobs-url.api.tevp_campaign_fetch_key');
    
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, "$url/$secretKey");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            // Execute cURL request
            $data = json_decode(curl_exec($ch));
    
            curl_close($ch);

            if((bool) ($data)){
                foreach ($data as $item) {
                    $item = (array) $item;
                    $item['is_fetch'] = 1;
                    $item['created_at'] = date('Y-m-d H:i:s');
                    GCList::firstOrCreate(['id' => $item['id']], $item);
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