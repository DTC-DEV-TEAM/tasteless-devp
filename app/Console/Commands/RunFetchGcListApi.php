<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GCListFetchJob;

class RunFetchGcListApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetchgclistapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch GC List Api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        GCListFetchJob::dispatch();

        return 0;
    }
}
