<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\StreamOutput;

class ApplicationTester
{
    private $application;

    private $output;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function run()
    {
        $this->output = new StreamOutput(fopen('php://memory', 'w', false));
        $input = null;

        return $this->application->run($input, $this->output);
    }

    public function getDisplay()
    {
        rewind($this->output->getStream());

        $display = stream_get_contents($this->output->getStream());

        return $display;
    }
}
