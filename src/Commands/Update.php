<?php

namespace TNM\USSD\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the USSD framework';

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
        $process = new Process(['composer', 'require', 'tnmdev/ussd']);

        $process->run(function ($type, $buffer) {
            $this->info($buffer);
        });
    }
}
