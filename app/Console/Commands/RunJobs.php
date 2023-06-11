<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RunJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:runjobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Email send';

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
        // Artisan::call('queue:restart');
        // Artisan::call('queue:work');
        // Artisan::call('queue:retry', ['--all' => true]);

        Artisan::call('queue:restart');
        // Retry all failed jobs
        $failedJobs = DB::table('failed_jobs')->get();
        foreach ($failedJobs as $failedJob) {

            Artisan::call('queue:retry', ['id' => $failedJob->id]);
        }
        Artisan::call('queue:work');
        
        return 0;
        
    }
}
