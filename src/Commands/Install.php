<?php

namespace TNM\USSD\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the USSD application';

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
        Artisan::call('make:ussd Welcome');
        $this->info('USSD application installed successfully');
    }
}
