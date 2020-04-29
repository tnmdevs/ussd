<?php

namespace TNM\USSD\Commands;

use Exception;
use Illuminate\Console\Command;
use TNM\USSD\Models\Session;

class CleanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:clean-up {--days=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old transactions';

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
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirm("This will delete all old session data. Are you sure?")) return;

        $days = $this->option('days') ?: 60;

        try {
            Session::where('created_at', '<', now()->subDays($days))->delete();
        } catch (Exception $exception) {
            $this->error(sprintf("Operation failed: %s", $exception->getMessage()));
        }

        $this->info('Session logs cleaned up successfully');
    }
}

