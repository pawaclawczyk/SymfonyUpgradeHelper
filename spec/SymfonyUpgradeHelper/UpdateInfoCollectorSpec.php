<?php

namespace spec\SymfonyUpgradeHelper;

use PhpSpec\ObjectBehavior;
use SymfonyUpgradeHelper\UpdateInfo;

class UpdateInfoCollectorSpec extends ObjectBehavior
{
    public function it_is_empty()
    {
        $this->getAll()->shouldReturn([]);
    }

    public function it_collects_info(UpdateInfo $logFoo, UpdateInfo $logBar)
    {
        $this->add($logFoo);
        $this->add($logBar);

        $this->getAll()->shouldReturn([$logFoo, $logBar]);
    }
}
