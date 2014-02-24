<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Input\StringInput;

class ApplicationTester
{
    private $application;

    private $output;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function run($command)
    {
        $this->output = new StreamOutput(fopen('php://memory', 'w', false));
        $input = new StringInput($command);

        return $this->application->run($input, $this->output);
    }

    public function getDisplay()
    {
        rewind($this->output->getStream());

        $display = stream_get_contents($this->output->getStream());

        return $display;
    }
}
