<?php

namespace TNM\USSD\Commands;

use Exception;
use Illuminate\Console\Command;
use TNM\USSD\Models\Payload;
use TNM\USSD\Models\Session;
use TNM\USSD\Models\SessionNumber;
use TNM\USSD\Models\TransactionTrail;

class CleanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:clean-up {--m|minutes= : Number of minutes to clear} {--f|force : Force to suppress confirmation}';

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
        $minutes = $this->option('minutes') ?: 10;

        if (!$this->option('force')) {
            if (!$this->confirm(sprintf("This will delete all session data older than %s minutes ago. Are you sure?", $minutes)))
                return;
        }

        try {
            Session::where('created_at', '<', now()->subMinutes($minutes))->delete();
            TransactionTrail::where('created_at', '<', now()->subMinutes($minutes))->delete();
            Payload::where('created_at', '<', now()->subMinutes($minutes))->delete();
            SessionNumber::where('created_at', '<', now()->subMinutes($minutes))->delete();

        } catch (Exception $exception) {
            $this->error(sprintf("Operation failed: %s", $exception->getMessage()));
        }

        $this->info('Session logs cleaned up successfully');
    }
}

