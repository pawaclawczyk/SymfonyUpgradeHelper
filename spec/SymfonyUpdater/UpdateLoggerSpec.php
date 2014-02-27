<?php

namespace spec\SymfonyUpdater;

use PhpSpec\ObjectBehavior;
use SymfonyUpdater\UpdateLog;

class UpdateLoggerSpec extends ObjectBehavior
{
    public function it_collects_no_logs()
    {
        $this->getAll()->shouldReturn([]);
    }

    public function it_collects_logs(UpdateLog $logFoo, UpdateLog $logBar)
    {
        $this->log($logFoo);
        $this->log($logBar);

        $this->getAll()->shouldReturn([$logFoo, $logBar]);
    }
}
