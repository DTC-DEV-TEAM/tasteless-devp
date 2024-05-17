<?php

namespace App\Jobs;

use App\GCList;
use App\QrCreation;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\MaxAttemptsExceededException;

class GCListFetchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $gc_list_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {  
        // ip
        $localhost = 'http://127.0.0.1:1000';
        $ip_address = 'http://192.168.4.93:8000';

        try {
            // Localhost fetch campaign
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("$ip_address/api/get-token", [
                'secret' => '84aad301b67368285f7b6f17eed0a064',
            ]);

            $get_token = $response->json('data.access_token');

            $redemption_list = Http::withHeaders([
                'Authorization' => 'Bearer ' . $get_token['data']['access_token'],
            ])->get("$ip_address/api/qr_creation");

            $gc_list_fetch = $redemption_list->json();
            
            if($gc_list_fetch['data']){

                foreach ($gc_list_fetch['data'] as $item) {
                    QrCreation::firstOrCreate(
                        ['id' => $item['id']],
                        $item
                    );
                }
            }else{
                return;
            }
        }catch(MaxAttemptsExceededException $e){
            $this->release(5);
        }
    }
}