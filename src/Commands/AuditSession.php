<?php

namespace TNM\USSD\Commands;


use Illuminate\Console\Command;
use TNM\USSD\Models\TransactionTrail;

class AuditSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:audit {session}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a message and response trail for a session';

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
        $trail = TransactionTrail::findBySession($this->argument('session'));

        if ($trail->isEmpty())
            $this->info(sprintf("Session %s was not found", $this->argument('session')));

        $this->table(['Message', 'Response', 'Timestamp'], $trail->map(function (TransactionTrail $trail) {
            return $trail->only(['message', 'response', 'created_at']);
        }));
    }
}
