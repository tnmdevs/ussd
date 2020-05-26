<?php

namespace TNM\USSD\Commands;

use Illuminate\Console\Command;
use TNM\USSD\Models\Payload;

class MonitorPayload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:payload {session}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all payload saved under the selected session';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $payload = Payload::findBySession($this->argument('session'));

        if ($payload->isEmpty()) {
            $this->info(sprintf("Session %s does not have saved payload", $this->argument('session')));
            return;
        }

        $this->table(['Key', 'Value', 'Timestamp'], $payload->map(function (Payload $payload) {
            return $payload->only(['key', 'value', 'created_at']);
        }));
    }
}
