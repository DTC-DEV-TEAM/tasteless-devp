<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;
use App\GCList;
use DB;

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
    public function handle($event)
    {
        $json = json_encode($event);
        $decoded = json_decode($json);
        $id = $decoded->data->id;

        DB::table('g_c_lists_devps')->where('id', $id)->update([
            'store_status' => 5
        ]);
    }
}
