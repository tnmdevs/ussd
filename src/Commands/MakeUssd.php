<?php

namespace TNM\USSD\Commands;

use Illuminate\Console\Command;

class MakeUssd extends Command
{
    /**
     * @var string
     */
    private $contents;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ussd {name} {--message=}';

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

        $this->writeToFile($fullPath);

        $this->info('Created screen successfully.');
    }

    public function getStub()
    {
        return __DIR__ . '/../stubs/screen.stub';
    }

    public function buildContents(): string
    {
        return $this->setContents()->replaceClassName()->replaceMessage()->build();
    }

    private function replaceMessage(): self
    {
        if ($this->option("message"))
            $this->contents = str_replace('{{message}}', $this->option('message'), $this->contents);

        return $this;
    }

    private function replaceClassName(): self
    {
        $this->contents = str_replace('{{class}}', $this->argument('name'), $this->contents);
        return $this;
    }

    private function setContents(): self
    {
        $this->contents = file_get_contents($this->getStub());
        return $this;
    }

    private function build(): string
    {
        return $this->contents;
    }

    private function writeToFile(string $fullPath): void
    {
        file_put_contents($fullPath, $this->buildContents());
    }
}
