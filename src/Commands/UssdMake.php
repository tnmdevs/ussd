<?php

namespace TNM\USSD\Commands;

use Illuminate\Console\Command;

class UssdMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ussd:make {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new USSD screen template';

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
        $path = app()->path() . '/Screens';
        $fullPath = sprintf("%s/%s.php", $path, $this->argument('name'));

        if (file_exists($fullPath)) {
            $this->error('Screen already exists!');
            die;
        }

        if (!is_dir($path)) mkdir($path);

        file_put_contents($fullPath, $this->replaceClassName());
        $this->info('Created screen successfully.');
    }

    public function getStub()
    {
        return __DIR__ . '/../stubs/screen.stub';
    }

    public function replaceClassName()
    {
        return str_replace('{{class}}', $this->argument('name'), file_get_contents($this->getStub()));
    }
}
