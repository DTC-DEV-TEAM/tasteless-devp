<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageFailed;
use Illuminate\Support\Facades\Log;
use App\GCList;
use DB;
use Illuminate\Support\Facades\Mail;

class LogFailedMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {

        // $json = json_encode($event);
        // $decoded = json_decode($json);
        // $id = $decoded->data->id;

        // $gc_lists_devps = DB::table('g_c_lists_devps')->where('id', $id);

        // if(in_array($gc_lists_devps->first()->store_status, [3,5,6])){

        //     $gc_lists_devps->update([
        //         'store_status' => 6
        //     ]);
        // }
        
    }
}
