<?php

namespace TNM\USSD\Commands;

use Illuminate\Console\Command;

class MakeScreenFactory extends Command
{
    private string $path = "/Factories";
    private string $onExists = "Factory already exists!";
    private string $onCreated = 'Created factory successfully.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ussd-factory {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make factory for rendering USSD screens';

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
        if (file_exists($this->getFullPath())) {
            $this->error($this->onExists);
            die;
        }

        if (!is_dir($this->getPath())) mkdir($this->getPath());

        file_put_contents($this->getFullPath(), $this->replaceClassName());
        $this->info($this->onCreated);
    }

    private function getStub(): string
    {
        return __DIR__ . '/../stubs/factory.stub';
    }

    private function replaceClassName(): array|bool|string
    {
        return str_replace('{{class}}', $this->argument('name'), file_get_contents($this->getStub()));
    }

    private function getPath(): string
    {
        return app()->path() . $this->path;
    }

    private function getFullPath(): string
    {
        return sprintf("%s/%s.php", $this->getPath(), $this->argument('name'));
    }
}
